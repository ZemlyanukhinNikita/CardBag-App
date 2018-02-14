<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $casts = [
        'discount' => 'int'
    ];
    protected $hidden = ['frontPhoto', 'backPhoto'];
    protected $fillable = ['user_id', 'title', 'category_id', 'front_photo', 'back_photo', 'discount', 'uuid', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function frontPhoto()
    {
        return $this->belongsTo(Photo::class, 'front_photo', 'id');
    }

    public function backPhoto()
    {
        return $this->belongsTo(Photo::class, 'back_photo', 'id');
    }
}

