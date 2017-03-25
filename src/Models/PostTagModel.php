<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*/
class PostTagModel extends Model
{
    protected $table        = 'post_tags';
    protected $primaryKey   = 'id';
    protected $fillable     = ['post_id', 'tag_id', 'user_id'];
    public $timeStamps      = false;
    public $updated_at      = false;
    public $created_at      = false;

    public function tag()
    {
        return $this->belogsTo(App\Models\TagModel::class);
    }
    public function post()
    {
        return $this->belongsTo(App\Models\PostModel::class);
    }
    public function user()
    {
        return $this->belongsTo(App\Models\UserModel::class);
    }
}
