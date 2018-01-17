<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        {
            return $this->belongsTo(Category::class);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        {
            return $this->belongsTo(User::class);
        }
    }
}

