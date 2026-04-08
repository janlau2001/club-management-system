<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubNews extends Model
{
    use HasFactory;

    protected $table = 'club_news';

    protected $fillable = [
        'club_id',
        'author_id',
        'title',
        'description',
        'image',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function author()
    {
        return $this->belongsTo(ClubUser::class, 'author_id');
    }
}
