<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'subscription_plan_id',
        'started_at',
        'ends_at',
        'renewal_date',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'renewal_date' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' &&
               (!$this->ends_at || $this->ends_at->isFuture());
    }

    public function renewalDue(): bool
    {
        return $this->renewal_date && $this->renewal_date->isPast();
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'ends_at' => Carbon::now(),
        ]);
    }
}
