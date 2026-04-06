<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = 'cities';
    
    protected $fillable = [
        'name',
        'state_id',
        'state_code',
        'country_id',
        'country_code',
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
     * Get the state that owns this city
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    
    /**
     * Get the country that owns this city
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    
    /**
     * Get full city name with state code
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ', ' . $this->state_code;
    }
    
    /**
     * Get city with state and country
     */
    public function getLocationAttribute(): string
    {
        $location = $this->name;
        if ($this->state) {
            $location .= ', ' . $this->state->name;
        }
        if ($this->country) {
            $location .= ', ' . $this->country->name;
        }
        return $location;
    }
    
    /**
     * Scope for active cities
     */
    public function scopeActive($query)
    {
        return $query->where('flag', true);
    }
    
    /**
     * Scope for cities by state
     */
    public function scopeByState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }
    
    /**
     * Scope for cities by country
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
        return $query->where('name', 'like', "%{$search}%");
    }
}