<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model
{
    use Sluggable;

    protected $guarded = [];
    protected $table = 'posts';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id' ,'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' ,'id');
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class , 'post_id' ,'id');
    }
}
