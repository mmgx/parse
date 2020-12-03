<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razmer extends Model
{
    use HasFactory;

    protected $fillable = ['marka_id', 'title', 'price', 'image', 'description', 'specifications'];
    public $timestamps = false;
}
