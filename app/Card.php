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
    protected $hidden = ['frontPhoto', 'backPhoto', 'barcodePhoto'];
    protected $fillable = [
        'user_id',
        'title',
        'category_id',
        'front_photo',
        'back_photo',
        'discount',
        'uuid',
        'updated_at',
        'barcode_photo',
        'barcode'
    ];

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

    public function barcodePhoto()
    {
        return $this->belongsTo(Photo::class, 'barcode_photo', 'id');
    }
}

