<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'image_path',
        'caption',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
