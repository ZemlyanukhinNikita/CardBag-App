<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $casts = [
        'discount' => 'int'
    ];

    protected $fillable = ['user_id', 'title', 'front_photo', 'back_photo', 'discount', 'uuid'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

