<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = ['tipo', 'descripcion'];

    // Relación uno a muchos con Comentario
    public function comentarios()
    {
        return $this->morphMany(Comentario::class, 'comentable');
    }
}
