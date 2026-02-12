import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import mysql.connector
import json
import sys

def find_similar_tickets():
    try:
        # 1. Parse Arguments
        current_subject = ""
        current_description = ""
        support_id = None
        
        # Simple argument parsing
        for arg in sys.argv[1:]:
            if arg.startswith('--subject='):
                current_subject = arg.split('=', 1)[1]
            elif arg.startswith('--description='):
                current_description = arg.split('=', 1)[1]
            elif arg.startswith('--support_id='):
                support_id = arg.split('=', 1)[1]

        if not current_subject or not current_description:
            print(json.dumps({"error": "Missing subject or description"}))
            return

        current_text = f"{current_subject} {current_description}"
        
        # 2. Database Connection
        db_config = {
            "host": "127.0.0.1",
            "user": "root",
            "password": "",
            "database": "oticket"
        }
        
        # Allow overriding db config via args (optional, for safety)
        for arg in sys.argv[1:]:
            if arg.startswith('--db_host='):
                db_config["host"] = arg.split('=')[1]
            elif arg.startswith('--db_user='):
                db_config["user"] = arg.split('=')[1]
            elif arg.startswith('--db_pass='):
                db_config["password"] = arg.split('=')[1]
            elif arg.startswith('--db_name='):
                db_config["database"] = arg.split('=')[1]
        
        conn = mysql.connector.connect(**db_config)

        # 3. Fetch Historical Data (Resolved or Closed tickets)
        query = """
            SELECT t.id, t.uuid, t.subject, t.description, t.resolved_at
            FROM tickets t
            JOIN ticket_statuses s ON t.status_id = s.id
            WHERE s.slug IN ('resolved', 'closed')
            AND t.description IS NOT NULL AND t.description != ''
        """
        
        params = []
        if support_id:
            query += " AND t.support_id = %s"
            params.append(support_id)
        
        df = pd.read_sql(query, conn, params=params)
        
        if df.empty:
            print(json.dumps([]))
            conn.close()
            return

        # 4. Prepare Data for Similarity
        df['combined_text'] = df['subject'] + " " + df['description']
        
        documents = df['combined_text'].tolist()
        documents.append(current_text) # Add current ticket at the end
        
        # 5. TF-IDF & Cosine Similarity
        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(documents)
        
        # Calculate cosine similarity between the last document (current) and all others
        cosine_sim = cosine_similarity(tfidf_matrix[-1], tfidf_matrix[:-1])
        
        # 6. Get Top Matches
        similarity_scores = cosine_sim[0]
        df['similarity'] = similarity_scores * 100 # Convert to percentage
        
        # Filter: > 40% similarity (lowered threshold slightly to ensure results in demo)
        similar_tickets = df[df['similarity'] > 40].sort_values(by='similarity', ascending=False).head(3)
        
        results = []
        for index, row in similar_tickets.iterrows():
            # Fetch solution (latest response by support/admin)
            # We assume the last response is the solution for resolved tickets
            ticket_id = row['id']
            sol_query = """
                SELECT message FROM ticket_responses 
                WHERE ticket_id = %s 
                ORDER BY created_at DESC LIMIT 1
            """
            cursor = conn.cursor()
            cursor.execute(sol_query, (ticket_id,))
            solution_row = cursor.fetchone()
            solution = solution_row[0] if solution_row else "No recorded solution."
            cursor.close()

            results.append({
                "id": row['id'],
                "uuid": row['uuid'],
                "subject": row['subject'],
                "similarity": round(row['similarity'], 1),
                "solution": solution[:200] + "..." if len(solution) > 200 else solution 
            })

        conn.close()
        print(json.dumps(results))

    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    find_similar_tickets()
