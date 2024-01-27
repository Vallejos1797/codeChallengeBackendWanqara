<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clima extends Model
{
    protected $fillable = ['ciudad', 'temperatura', 'humedad'];

    protected $appends = ['temperatura_fahrenheit'];

    // Relación uno a muchos con Comentario
    public function comentarios()
    {
        return $this->morphMany(Comentario::class, 'commentable');
    }

    public function getTemperaturaFahrenheitAttribute()
    {
        // Calcula la temperatura en Fahrenheit a partir de la temperatura en Celsius
        return ($this->temperatura * 9/5) + 32;
    }
}
