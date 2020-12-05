<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razmer extends Model
{
    use HasFactory;

    protected $fillable = ['marka_id', 'title', 'price', 'image', 'description', 'specifications', 'url'];
    public $timestamps = false;

    /**
     * Получить марку указанной модели
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marka()
    {
        return $this->belongsTo(Marka::class, 'marka_id', 'marka_id');
    }
}
