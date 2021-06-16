<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Disable mass assignment protection
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
