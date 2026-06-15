<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'slug',
        'logo_url',
        'subscription_plan_id',
        'subscription_status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function caisses()
    {
        return $this->hasMany(Caisse::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function fournisseurs()
    {
        return $this->hasMany(Fournisseur::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function isSubscriptionActive(): bool
    {
        return $this->subscription_status === 'active' &&
               $this->subscription &&
               $this->subscription->isActive();
    }

    public function getFeatureLimit(string $feature): ?int
    {
        if (!$this->subscription || !$this->subscription->plan) {
            return null;
        }

        $features = $this->subscription->plan->features ?? [];
        return $features[$feature] ?? null;
    }
}
