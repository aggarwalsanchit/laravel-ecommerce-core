<?php
// app/Models/ProductAttributeValue.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    protected $table = 'product_attribute_values';

    protected $fillable = [
        'product_id', 'attribute_id', 'attribute_value_id',
        'value', 'price_adjustment', 'weight_adjustment'
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'weight_adjustment' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the attribute value (for select/multiselect types)
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted value for display
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->attributeValue) {
            return $this->attributeValue->display_name;
        }
        
        $attribute = $this->attribute;
        if ($attribute && $attribute->type === 'color') {
            return "<div style='width: 25px; height: 25px; background-color: {$this->value}; border-radius: 50%; display: inline-block;'></div>";
        }
        
        if ($attribute && $attribute->unit) {
            return $this->value . ' ' . $attribute->unit;
        }
        
        return $this->value ?? '-';
    }

    /**
     * Get final price after adjustment
     */
    public function getFinalPriceAttribute($basePrice): float
    {
        return $basePrice + $this->price_adjustment;
    }

    /**
     * Get final weight after adjustment
     */
    public function getFinalWeightAttribute($baseWeight): float
    {
        return $baseWeight + $this->weight_adjustment;
    }
}