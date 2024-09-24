<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
