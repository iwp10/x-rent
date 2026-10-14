<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'license_plate',
        'engine_cc',
        'transmission',
        'helm_included',
        'condition_notes',
        'status',
        'price_daily',
        'price_weekly',
        'price_monthly',
        'image',
        'type',
        'brand',
        'year',
        'facilities',
        'features',
        'gallery',
        'specs',
        'requirements',
        'badge',
        'subtitle',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'is_available',
        'status_label',
        'prices',
        'category_label',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'engine_cc' => 'integer',
            'helm_included' => 'integer',
            'price_daily' => 'integer',
            'price_weekly' => 'integer',
            'price_monthly' => 'integer',
            'features' => 'array',
            'gallery' => 'array',
            'specs' => 'array',
            'requirements' => 'array',
        ];
    }

    /**
     * Category relation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Bookings relation.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Accessor: is_available.
     */
    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'available',
        );
    }

    /**
     * Accessor: status_label.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    'available' => 'Tersedia',
                    'rented' => 'Sedang Disewa',
                    'maintenance' => 'Dalam Perawatan',
                    default => 'Tersedia',
                };
            },
        );
    }

    /**
     * Accessor: prices array for frontend reactive period toggle.
     */
    protected function prices(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'daily' => (int) $this->price_daily,
                'weekly' => (int) $this->price_weekly,
                'monthly' => (int) $this->price_monthly,
            ],
        );
    }

    /**
     * Accessor: category_label.
     */
    protected function categoryLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->category ? $this->category->name : ucfirst($this->type),
        );
    }
}
