<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*/
class PostModel extends Model
{
    protected $table 		= 'posts';
    protected $primarykey 	= 'id';
    protected $fillable		= ['title', 'content', 'user_id', 'deleted'];
    public $timestamps		= true;

    public function postTag()
    {
        return $this->hasMany(App\Models\PostTagModel::class);
        // var_dump($this->postTag());
    }


}
