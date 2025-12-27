<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    
    /**
     * deleted because it's default built in by laravel
     * protected $table = 'articles';
     * protected $primaryKey = 'id';
     * public $timestamps = true;
     */

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    /**
     * auto generate uuid prefix article-
     */
    protected static function booted(): void
    {
        static::creating(function($article) {
            if(empty($article->id)) {
                $article->id = (string) Str::uuid();
            }
        });
    }

    /**
     * relation: article belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
