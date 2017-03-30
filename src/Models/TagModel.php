<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*/
class TagModel extends Model
{
    protected $table        = 'tags';
    protected $primaryKey   = 'id';
    protected $fillable     = ['tags'];
    public $timeStamps      = false;
    public $updated_at      = false;
    public $created_at      = false;

    public function postTag()
    {
        return $this->hasMany(PostTagModel::class);
    }
}
