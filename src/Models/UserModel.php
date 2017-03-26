<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostModel;

/**
*
*/
class UserModel extends Model
{
    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $fillable     = ['username', 'email', 'password', 'role_id', 'deleted'];
    public $timeStamps      = true;

    public function post()
    {
        return $this->hashMany(PostModel::class);
    }
}
