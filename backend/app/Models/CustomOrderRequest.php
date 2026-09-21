<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomOrderRequest extends Model
{
    use HasFactory;

    protected $table = 'custom_order_requests';

    protected $fillable = [
        'reference_id',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'category',
        'selected_palette',
        'custom_colors',
        'custom_color_notes',
        'size_preference',
        'personalization',
        'design_notes',
        'urgency',
        'budget_range',
        'reference_image_url',
        'reference_image_name',
        'status',
        'quoted_price',
        'admin_notes',
    ];

    protected $casts = [
        'custom_colors' => 'array',
        'quoted_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge_class',
    ];

    /**
     * Relationship to Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'in_review' => 'Artisan Reviewing',
            'quoted' => 'Quote Provided',
            'approved' => 'Approved / Ready to Craft',
            'in_progress' => 'In Crafting',
            'completed' => 'Completed',
            'rejected' => 'Declined',
            default => ucfirst(str_replace('_', ' ', $this->status ?? 'pending')),
        };
    }

    /**
     * Badge CSS class for status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'in_review' => 'bg-blue-50 text-blue-700 border-blue-200',
            'quoted' => 'bg-purple-50 text-purple-700 border-purple-200',
            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'in_progress' => 'bg-rose-50 text-rose-700 border-rose-200',
            'completed' => 'bg-green-50 text-green-700 border-green-200',
            'rejected' => 'bg-stone-100 text-stone-600 border-stone-200',
            default => 'bg-stone-50 text-stone-700 border-stone-200',
        };
    }
}
