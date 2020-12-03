<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['subcategory_id', 'category_id', 'title', 'url', 'name', 'image'];
    public $timestamps = false;
}
