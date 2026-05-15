<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'category_id',
        'name',
        'slug',
        'contact_number',
        'email',
        'address',
        'city',
        'description',
        'status',
    ];

    public static function booted(): void
    {
        static::creating(function (Business $business): void {
            if (! $business->slug) {
                $business->slug = Str::slug($business->name).'-'.Str::random(6);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(BusinessPhoto::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(BusinessHour::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }
}
