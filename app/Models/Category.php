<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image', 'url'];
    public $timestamps = false;

    /**
     * Получить все подкатегории указанной категории
     * @return HasMany
     */
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
