<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marka extends Model
{
    use HasFactory;

    protected $fillable = ['marka_id', 'subcategory_id', 'name', 'title', 'price', 'description', 'image'];
}
