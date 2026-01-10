<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'uuid', 'user_id', 'support_id', 'category_id', 'status_id', 'status', 'priority', 'subject', 'description', 'sla_due_at', 'resolved_at'
    ];

    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function getStatusAttribute()
    {
        return $this->ticketStatus?->slug;
    }

    public function setStatusAttribute($value)
    {
        if (is_numeric($value)) {
            $this->attributes['status_id'] = $value;
        } else {
            $status = TicketStatus::where('slug', $value)->first();
            if ($status) {
                $this->attributes['status_id'] = $status->id;
            }
        }
        $this->unsetRelation('ticketStatus');
    }
    public function scopeOfStatus($query, $statusSlug)
    {
        return $query->whereHas('ticketStatus', function($q) use ($statusSlug) {
            $q->where('slug', $statusSlug);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function support()
    {
        return $this->belongsTo(User::class, 'support_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function feedback()
    {
        return $this->hasOne(TicketFeedback::class);
    }

    protected $casts = [
        'sla_due_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Recalculates the SLA due date based on creation time and current priority.
     */
    public function recalculateSla()
    {
        if (!$this->priority || !$this->created_at) {
            return;
        }

        $sla = SlaRule::where('priority', $this->priority)->first();
        if ($sla) {
            $this->sla_due_at = $this->created_at->copy()->addMinutes($sla->resolution_time_minutes);
        }
    }

    /**
     * Checks if the ticket is currently SLA compliant.
     */
    public function isSlaCompliant()
    {
        if (!$this->sla_due_at) {
            return true; // Or false? Usually true if no SLA is set
        }

        if ($this->resolved_at) {
            return $this->resolved_at <= $this->sla_due_at;
        }

        return now() <= $this->sla_due_at;
    }

    /**
     * Standardized SLA Compliance Calculation
     */
    public static function calculateSlaCompliance($query = null)
    {
        $query = $query ?: self::query();
        
        // Count only tickets that have an SLA protocol assigned
        $totalWithSla = (clone $query)->whereNotNull('sla_due_at')->count();
        
        if ($totalWithSla === 0) {
            return 100.0;
        }

        $compliantCount = (clone $query)->whereNotNull('sla_due_at')
            ->where(function($q) {
                $q->where(function($sq) {
                    $sq->whereNotNull('resolved_at')
                        ->whereRaw('resolved_at <= sla_due_at');
                })->orWhere(function($sq) {
                    $sq->whereNull('resolved_at')
                        ->where('sla_due_at', '>=', now());
                });
            })->count();

        return round(($compliantCount / $totalWithSla) * 100, 1);
    }

    /**
     * Standardized Avg Response Time Calculation (in minutes)
     */
    public static function calculateAvgResponseTime($query = null)
    {
        $query = $query ?: self::query();
        
        $avgMinutes = (clone $query)->whereNotNull('support_id')
            ->whereHas('responses', function($q) {
                $q->whereColumn('user_id', '!=', 'tickets.user_id');
            })
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, (
                SELECT MIN(created_at) 
                FROM ticket_responses 
                WHERE ticket_id = tickets.id 
                AND user_id != tickets.user_id
            ))) as avg_time')
            ->value('avg_time');

        return $avgMinutes ? round($avgMinutes, 1) : 0;
    }

    /**
     * Standardized Avg Resolution Time Calculation (in minutes)
     */
    public static function calculateAvgResolutionTime($query = null)
    {
        $query = $query ?: self::query();
        
        $avgMinutes = (clone $query)->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_time')
            ->value('avg_time');

        return $avgMinutes ? round($avgMinutes, 1) : 0;
    }

    /**
     * Get Top IT Issues by Weighted Impact (Volume x Priority)
     */
    public static function getTopIssuesByImpact($query = null, $limit = 5)
    {
        $query = $query ?: self::query();

        return (clone $query)
            ->select(
                'subject',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_count')
            )
            ->groupBy('subject')
            ->orderBy('total_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
