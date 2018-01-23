<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $casts = [
        'discount' => 'int'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
