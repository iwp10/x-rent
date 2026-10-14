<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_code',
        'vehicle_id',
        'customer_name',
        'customer_phone',
        'customer_type',
        'start_date',
        'duration_type',
        'duration_count',
        'delivery_option',
        'total_price',
        'status',
        'notes',
        'delivery_fee',
        'deposit_amount',
        'pickup_address',
        'payment_status',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'status_label',
        'step_index',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'duration_count' => 'integer',
            'total_price' => 'integer',
            'delivery_fee' => 'integer',
            'deposit_amount' => 'integer',
        ];
    }

    /**
     * Vehicle relation.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Accessor: status_label.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    'pending' => 'Menunggu Konfirmasi',
                    'confirmed' => 'Pesanan Diterima (Siap Diambil)',
                    'ongoing' => 'Sedang Disewa (Aktif)',
                    'completed' => 'Selesai & Direfund',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($this->status),
                };
            },
        );
    }

    /**
     * Accessor: step_index (1 to 4 for Progress Stepper).
     */
    protected function stepIndex(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    'pending' => 1,
                    'confirmed' => 2,
                    'ongoing' => 3,
                    'completed' => 4,
                    default => 1,
                };
            },
        );
    }
}
