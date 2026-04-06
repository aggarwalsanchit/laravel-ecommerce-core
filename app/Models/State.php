<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $table = 'states';
    
    protected $fillable = [
        'name',
        'country_id',
        'country_code',
        'fips_code',
        'iso2',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId'
    ];
    
    protected $casts = [
        'flag' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
    
    /**
     * Get the country that owns this state
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    
    /**
     * Get all cities for this state
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    
    /**
     * Get full state name with country code
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ', ' . $this->country_code;
    }
    
    /**
     * Scope for active states
     */
    public function scopeActive($query)
    {
        return $query->where('flag', true);
    }
    
    /**
     * Scope for states by country
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
    
    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('iso2', 'like', "%{$search}%");
    }
}