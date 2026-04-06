<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';
    
    protected $fillable = [
        'name',
        'iso3',
        'iso2',
        'phonecode',
        'capital',
        'currency',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'subregion',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'wikiDataId'
    ];
    
    protected $casts = [
        'flag' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
    
    /**
     * Get all states for this country
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
    
    /**
     * Get all cities for this country
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    
    /**
     * Get formatted phone code with plus sign
     */
    public function getFormattedPhoneCodeAttribute(): string
    {
        return $this->phonecode ? '+' . $this->phonecode : '';
    }
    
    /**
     * Get country flag URL
     */
    public function getFlagUrlAttribute(): string
    {
        if ($this->emoji) {
            return $this->emoji;
        }
        return asset('flags/' . strtolower($this->iso2) . '.png');
    }
    
    /**
     * Scope for active countries
     */
    public function scopeActive($query)
    {
        return $query->where('flag', true);
    }
    
    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('iso2', 'like', "%{$search}%")
                     ->orWhere('iso3', 'like', "%{$search}%");
    }
}