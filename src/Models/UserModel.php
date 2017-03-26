<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*/
class UserModel extends Model
{
    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $fillable     = ['username', 'email', 'password', 'role_id', 'deleted'];
    public $timeStamps      = true;

    public function postTag()
    {
        return $this->hasMany(PostTagModel::class,'user_id');
    }
}
