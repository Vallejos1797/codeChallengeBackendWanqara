<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $table = 'weathers';
    protected $fillable = ['city', 'temperature', 'humidity'];

    protected $appends = ['temperature_fahrenheit'];

    // Relation one to many with Comment
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'comentable');
    }

    public function getTemperatureFahrenheitAttribute(): float|int
    {
        // Calcula la temperature en Fahrenheit a partir de la temperature en Celsius
        return ($this->temperature * 9/5) + 32;
    }
}
