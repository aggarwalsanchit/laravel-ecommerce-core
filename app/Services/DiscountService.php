<?php
// app/Services/DiscountService.php

namespace App\Services;

use App\Models\Discount;
use App\Models\Product;

class DiscountService
{
    /**
     * Get best applicable discount for a product
     */
    public static function getBestDiscountForProduct(Product $product, $cartTotal = null, $user = null)
    {
        $discounts = Discount::where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('discount_value', 'desc')
            ->get();

        $bestDiscount = null;
        $bestSavings = 0;

        foreach ($discounts as $discount) {
            if ($discount->isValid($product, $user, $cartTotal)) {
                $savings = $discount->calculateDiscount($product->price);
                if ($savings > $bestSavings) {
                    $bestSavings = $savings;
                    $bestDiscount = $discount;
                }
            }
        }

        return $bestDiscount;
    }

    /**
     * Apply discount to product and return discounted price
     */
    public static function applyDiscountToProduct(Product $product, $discountCode = null, $user = null)
    {
        $discount = null;

        if ($discountCode) {
            // Apply coupon code discount
            $discount = Discount::where('code', $discountCode)
                ->where('status', true)
                ->where(function ($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->first();

            if (!$discount || !$discount->isValid($product, $user)) {
                $discount = null;
            }
        }

        if (!$discount) {
            // Get best automatic discount
            $discount = self::getBestDiscountForProduct($product, null, $user);
        }

        if ($discount) {
            $discountInfo = $discount->getDiscountInfo($product);
            return [
                'has_discount' => true,
                'original_price' => $product->price,
                'final_price' => $discountInfo['final_price'],
                'discount_amount' => $discountInfo['discount_amount'],
                'save_percentage' => $discountInfo['save_percentage'],
                'discount_name' => $discountInfo['discount_name'],
                'discount_code' => $discountInfo['discount_code'],
                'discount_type' => $discountInfo['discount_type'],
                'badge_text' => $discountInfo['badge_text'],
                'badge_color' => $discountInfo['badge_color'],
            ];
        }

        return [
            'has_discount' => false,
            'original_price' => $product->price,
            'final_price' => $product->price,
            'discount_amount' => 0,
            'save_percentage' => 0,
        ];
    }

    /**
     * Apply discount to cart total
     */
    public static function applyDiscountToCart($cartItems, $cartTotal, $discountCode = null, $user = null)
    {
        if (!$discountCode) {
            return [
                'has_discount' => false,
                'original_total' => $cartTotal,
                'final_total' => $cartTotal,
                'discount_amount' => 0,
            ];
        }

        $discount = Discount::where('code', $discountCode)
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$discount) {
            return [
                'has_discount' => false,
                'original_total' => $cartTotal,
                'final_total' => $cartTotal,
                'discount_amount' => 0,
                'message' => 'Invalid or expired coupon code.'
            ];
        }

        // Check if discount applies to any product in cart
        $applicableProducts = [];
        $totalDiscount = 0;

        foreach ($cartItems as $item) {
            if ($discount->isProductEligible($item['product'])) {
                $applicableProducts[] = $item['product']->id;
                $totalDiscount += $discount->calculateDiscount($item['product']->price, $item['quantity']);
            }
        }

        if (empty($applicableProducts) && $discount->target_type !== 'all_products') {
            return [
                'has_discount' => false,
                'original_total' => $cartTotal,
                'final_total' => $cartTotal,
                'discount_amount' => 0,
                'message' => 'This coupon is not applicable to any items in your cart.'
            ];
        }

        // Check minimum purchase amount
        if ($discount->min_purchase_amount && $cartTotal < $discount->min_purchase_amount) {
            return [
                'has_discount' => false,
                'original_total' => $cartTotal,
                'final_total' => $cartTotal,
                'discount_amount' => 0,
                'message' => 'Minimum purchase amount of $' . number_format($discount->min_purchase_amount, 2) . ' required.'
            ];
        }

        $finalTotal = max(0, $cartTotal - $totalDiscount);

        return [
            'has_discount' => true,
            'original_total' => $cartTotal,
            'final_total' => $finalTotal,
            'discount_amount' => $totalDiscount,
            'discount_name' => $discount->name,
            'discount_code' => $discount->code,
            'discount_percentage' => $discount->discount_type === 'percentage' ? $discount->discount_value : round(($totalDiscount / $cartTotal) * 100, 2),
            'message' => 'Coupon applied successfully!'
        ];
    }

    /**
     * Get all active discounts for a product
     */
    public static function getApplicableDiscounts(Product $product, $user = null)
    {
        $discounts = Discount::where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('is_featured', 'desc')
            ->get();

        $applicableDiscounts = [];
        foreach ($discounts as $discount) {
            if ($discount->isValid($product, $user)) {
                $applicableDiscounts[] = $discount->getDiscountInfo($product);
            }
        }

        return $applicableDiscounts;
    }
}
