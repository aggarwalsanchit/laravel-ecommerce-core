<?php
// app/Models/Discount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'buy_quantity',
        'get_quantity',
        'free_shipping_only',
        'target_type',
        'target_ids',
        'min_purchase_amount',
        'max_usage_per_user',
        'total_usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'stackable',
        'user_groups'
    ];

    protected $casts = [
        'target_ids' => 'array',
        'user_groups' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'stackable' => 'boolean',
        'free_shipping_only' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'buy_quantity' => 'integer',
        'get_quantity' => 'integer',
        'max_usage_per_user' => 'integer',
        'total_usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    public function isValid($product = null, $user = null, $cartTotal = null)
    {
        if (!$this->status) return false;

        $now = now();
        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;

        if ($this->total_usage_limit && $this->used_count >= $this->total_usage_limit) return false;

        if ($this->min_purchase_amount && $cartTotal && $cartTotal < $this->min_purchase_amount) return false;

        if ($this->user_groups && !empty($this->user_groups) && $user) {
            $userGroup = $user->group ?? 'regular';
            if (!in_array($userGroup, $this->user_groups)) return false;
        }

        if ($product && !$this->isProductEligible($product)) return false;

        return true;
    }

    public function isProductEligible($product)
    {
        if ($this->target_type === 'all_products') {
            return true;
        }

        if (!$this->target_ids || empty($this->target_ids)) {
            return false;
        }

        switch ($this->target_type) {
            case 'products':
                return in_array($product->id, $this->target_ids);

            case 'categories':
                return in_array($product->category_id, $this->target_ids);

            case 'subcategories':
                $productSubcategoryIds = $product->subcategories->pluck('id')->toArray();
                return !empty(array_intersect($productSubcategoryIds, $this->target_ids));

            case 'colors':
                $productColorIds = $product->colors->pluck('id')->toArray();
                return !empty(array_intersect($productColorIds, $this->target_ids));

            case 'sizes':
                $productSizeIds = $product->sizes->pluck('id')->toArray();
                return !empty(array_intersect($productSizeIds, $this->target_ids));

            case 'custom_attributes':
                $targetConfig = $this->target_ids;
                $attributeId = $targetConfig['attribute_id'] ?? null;
                $attributeValueIds = $targetConfig['attribute_value_ids'] ?? [];

                if (!$attributeId || empty($attributeValueIds)) {
                    return false;
                }

                $productAttributeValues = $product->customAttributes()
                    ->where('attribute_id', $attributeId)
                    ->pluck('attribute_value_id')
                    ->toArray();

                return !empty(array_intersect($productAttributeValues, $attributeValueIds));

            case 'clearance':
                return $product->is_clearance || $product->clearance == true;

            default:
                return false;
        }
    }

    public function calculateDiscount($price, $quantity = 1)
    {
        $subtotal = $price * $quantity;

        switch ($this->discount_type) {
            case 'percentage':
                $discount = ($subtotal * $this->discount_value) / 100;
                break;
            case 'fixed_amount':
                $discount = min($this->discount_value, $subtotal);
                break;
            case 'buy_x_get_y':
                if ($this->buy_quantity && $this->get_quantity) {
                    $freeItems = floor($quantity / ($this->buy_quantity + $this->get_quantity)) * $this->get_quantity;
                    $discount = $freeItems * $price;
                } else {
                    $discount = 0;
                }
                break;
            default:
                $discount = 0;
        }

        return $discount;
    }

    public function getDiscountInfo($product, $quantity = 1)
    {
        $discountAmount = $this->calculateDiscount($product->price, $quantity);
        $finalPrice = max(0, $product->price - ($discountAmount / $quantity));

        return [
            'discount_id' => $this->id,
            'discount_name' => $this->name,
            'discount_code' => $this->code,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'discount_amount' => $discountAmount,
            'original_price' => $product->price,
            'final_price' => $finalPrice,
            'save_amount' => $discountAmount / $quantity,
            'save_percentage' => $product->price > 0 ? round(($discountAmount / ($product->price * $quantity)) * 100, 2) : 0,
            'badge_text' => $this->getBadgeText(),
            'badge_color' => $this->getBadgeColor()
        ];
    }

    private function getBadgeText()
    {
        switch ($this->discount_type) {
            case 'percentage':
                return $this->discount_value . '% OFF';
            case 'fixed_amount':
                return '$' . number_format($this->discount_value, 0) . ' OFF';
            case 'buy_x_get_y':
                return 'Buy ' . $this->buy_quantity . ' Get ' . $this->get_quantity . ' Free';
            case 'free_shipping':
                return 'Free Shipping';
            default:
                return $this->name;
        }
    }

    private function getBadgeColor()
    {
        if ($this->is_featured) return 'danger';
        if ($this->discount_type === 'percentage') return 'warning';
        if ($this->discount_type === 'free_shipping') return 'info';
        return 'success';
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
