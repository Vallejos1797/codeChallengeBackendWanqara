<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $table = 'registers';

    protected $fillable = ['user_id', 'type', 'description'];

    // Relation with User model
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation one to many with Comment
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'comentable');
    }
}

