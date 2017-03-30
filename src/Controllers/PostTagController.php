<?php

namespace App\Controllers;

use App\Models\PostTagModel;

class PostTagController extends Controller
{
    public function get()
    {
        return PostTagModel::get();
    }

    public function getTagByUser()
    {
        if (isset($_SESSION['login']['id']) && !is_null($_SESSION['login']['id'])) {
            $postTag =PostTagModel::where('user_id', $_SESSION['login']['id'])->get()->toArray();

            if (!is_null($postTag)) {
                foreach ($postTag as $val) {
                    $fetchTag = PostTagModel::find($val['id']);
                    $tag[] = $fetchTag->tag->toArray();
                }
                return $tag;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function getTagByPost($request, $response, $args)
    {
        $postTag = PostTagModel::where('post_id', $args['id'])->get()
                   ->toArray();
        if (!is_null($postTag)) {
            foreach ($postTag as $val) {
                $fetchTag = PostTagModel::find($val['id']);
                $tag[] = $fetchTag->tag->toArray();
            }
            return $tag;
        } else {
            return [];
        }

    }

    public function getPostByTag($request, $response, $args)
    {
        $postTag = PostTagModel::where('tag_id',$args['id'])->get()->toArray();

        if (!is_null($postTag)) {
            foreach ($postTag as $val) {
                $fetchPost = PostTagModel::find($val['id']);
                $post[]    = $fetchPost->post->toArray();
            }
            var_dump($post);
            die();
            return $post;
        } else {
            return [];
        }
    }

    public function postAdd($request, $response, $args)
    {
        $postTag = PostTagModel::where('post_id', $request['post_id'])->where('tag_id', $request['tag_id'])
                    ->where('user_id',$_SESSION['login']['id'])->first();
        if (is_null($postTag)) {
            PostTagModel::create(
                [
                    'post_id'   => $request['post_id'],
                    'tag_id'    => $request['tag_id'],
                    'user_id'   => $_SESSION['login']['id'],
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
