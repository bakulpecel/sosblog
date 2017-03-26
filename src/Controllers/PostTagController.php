<?php

namespace App\Controllers;

use App\Models\PostTagModel;

class PostTagController extends Controller
{
    public function get()
    {
        return PostTagModel::get();
    }
    public function postAdd($request, $response)
    {
        $postTag = PostTagModel::where('post_id', $request['post_id'])->where('tag_id', $request['tag_id'])
                    ->where('user_id',$request['user_id'])->first();
        if (is_null($postTag)) {
            PostTagModel::create(
                [
                    'post_id'   => $request['post_id'],
                    'tag_id'    => $request['tag_id'],
                    'user_id'   => $request['user_id'],
                ]
            );
        }
    }
    public function delete($request, $response)
    {
        $postTag = PostTagModel::where('post_id', $request['post_id'])->where('tag_id', $request['tag_id'])
                    ->where('user_id',$request['user_id'])->first();
        $postTag->delete();
    }
}
