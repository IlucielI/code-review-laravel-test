<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class, 'authro_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
