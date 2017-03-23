<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class CommentModel extends Model
{
    protected $table        = 'comments';
    protected $primaryKey   = 'id';
    protected $fillable     = ['comment', 'post_id', 'user_id', 'deleted'];
    public $timeStamps      = true;
}