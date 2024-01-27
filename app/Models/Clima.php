<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clima extends Model
{
    protected $fillable = ['ciudad', 'temperatura', 'humedad'];

    // Relación uno a muchos con Comentario
    public function comentarios()
    {
        return $this->morphMany(Comentario::class, 'comentable');
    }
}
