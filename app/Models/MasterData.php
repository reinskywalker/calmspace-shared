<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'audio_video_url',
        'thumbnail_image_url',
        'content',
        'posted_by',
        'status',
        'user_id'
    ];

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
