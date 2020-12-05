<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['subcategory_id', 'category_id', 'title', 'url', 'image'];
    public $timestamps = false;

    /**
     * Получить категорию указанной подкатегории
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Получить все марки указанной подкатегории
     * @return HasMany
     */
    public function markas()
    {
        return $this->hasMany(Marka::class, 'subcategory_id', 'subcategory_id');
    }
}
