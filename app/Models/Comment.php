<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['description','comentable_type','comentable_id'];

    public function comentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
