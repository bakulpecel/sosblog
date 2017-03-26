<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel;

/**
*
*/
class PostModel extends Model
{
    protected $table 		= 'posts';
    protected $primarykey 	= 'id';
    protected $fillable		= ['title', 'content', 'user_id', 'deleted'];
    public $timestamps		= true;

    /**
    *
    */
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    /**
    *
    */
    public function postTag()
    {
        return $this->hasMany(App\Models\PostTagModel::class);
    }
}