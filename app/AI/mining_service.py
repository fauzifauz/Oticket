import pandas as pd
from sklearn.cluster import KMeans
import mysql.connector
import json
import sys

def perform_mining():
    try:
        # 1. Database Connection
        db_config = {
            "host": "127.0.0.1",
            "user": "root",
            "password": "",
            "database": "oticket"
        }

        if len(sys.argv) > 1:
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

        # 2. Fetch Data with Filters
        year = None
        month = None
        week = None

        if len(sys.argv) > 1:
            for arg in sys.argv[1:]:
                if arg.startswith('--year='):
                    year = arg.split('=')[1]
                elif arg.startswith('--month='):
                    month = arg.split('=')[1]
                elif arg.startswith('--week='):
                    week = arg.split('=')[1]

        query = """
            SELECT u.department, c.name as category, t.created_at 
            FROM tickets t
            JOIN users u ON t.user_id = u.id
            JOIN ticket_categories c ON t.category_id = c.id
            WHERE 1=1
        """
        
        params = []
        if year:
            query += " AND YEAR(t.created_at) = %s"
            params.append(year)
        if month:
            query += " AND DATE_FORMAT(t.created_at, '%%Y-%%m') = %s"
            params.append(month)
        if week:
            # Assuming week is a date string belonging to the target week
            query += " AND YEARWEEK(t.created_at, 1) = YEARWEEK(%s, 1)"
            params.append(week)

        df = pd.read_sql(query, conn, params=params)
        conn.close()

        if df.empty:
            print(json.dumps({"error": "No data available for mining"}))
            return

        # 3. Aggregate Data per Department
        # Count total reports and group categories
        def get_top_categories(x):
            counts = x.value_counts()
            top_2 = counts.index[:2].tolist()
            return ", ".join(top_2)

        dept_stats = df.groupby('department').agg(
            report_count=('category', 'size'),
            top_category=('category', get_top_categories)
        ).reset_index()

        # 4. K-Means Clustering
        # We cluster based on the number of reports
        X = dept_stats[['report_count']]
        
        # Determine number of clusters (max 3, but not more than number of unique report counts)
        n_clusters = min(3, len(dept_stats))
        if n_clusters < 1: n_clusters = 1
        
        kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
        dept_stats['cluster'] = kmeans.fit_predict(X)

        # Identify cluster intensity (High, Medium, Low)
        # Cluster with highest mean report_count is 'High'
        cluster_means = dept_stats.groupby('cluster')['report_count'].mean().sort_values(ascending=False)
        cluster_map = {cluster: i for i, cluster in enumerate(cluster_means.index)}
        # 0: High, 1: Medium, 2: Low (based on mapping)
        
        intensity_labels = {0: "High", 1: "Medium", 2: "Low"}
        dept_stats['intensity'] = dept_stats['cluster'].map(cluster_map).map(intensity_labels)

        # 5. Prepare JSON Result
        result = {
            "clusters": [],
            "departments": dept_stats.to_dict(orient='records')
        }
        
        for cluster_id, intensity in intensity_labels.items():
            depts = dept_stats[dept_stats['cluster'].map(cluster_map) == cluster_id]['department'].tolist()
            if depts:
                result["clusters"].append({
                    "intensity": intensity,
                    "count": len(depts),
                    "departments": depts
                })

        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    perform_mining()
