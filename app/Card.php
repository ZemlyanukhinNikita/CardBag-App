<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    public $casts = [
        'discount' => 'int'
    ];

    protected $fillable = ['user_id', 'title', 'category_id', 'front_photo', 'back_photo', 'discount', 'uuid'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

