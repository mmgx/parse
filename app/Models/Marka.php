<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marka extends Model
{
    use HasFactory;

    protected $fillable = ['marka_id', 'subcategory_id', 'title', 'price', 'description', 'image', 'url'];
    public $timestamps = false;

    /**
     * Получить подкатегорию, которой принадлежит марка
     * @return BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'subcategory_id');
    }

    public function razmer()
    {
        return $this->hasMany(Razmer::class, 'marka_id', 'marka_id');
    }
}
