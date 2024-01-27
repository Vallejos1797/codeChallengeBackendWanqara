<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['contenido'];

    // Relación polimórfica con Clima
    public function clima()
    {
        return $this->morphTo();
    }

    // Relación polimórfica con Registro
    public function registro()
    {
        return $this->morphTo();
    }
}
