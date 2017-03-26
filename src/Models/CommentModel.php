<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel;

/**
* 
*/
class CommentModel extends Model
{
    protected $table        = 'comments';
    protected $primaryKey   = 'id';
    protected $fillable     = ['comment', 'post_id', 'user_id', 'deleted'];
    public $timeStamps      = true;

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}