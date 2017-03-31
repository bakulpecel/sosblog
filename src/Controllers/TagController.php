<?php

namespace App\Controllers;

use App\Models\TagModel;

class TagController extends Controller
{
    public static function addTag($tag)
    {
        TagModel::create(['tags'  => trim(ucwords(strtolower($tag)))]);
    }
    public static function getTagByName($name)
    {
        return TagModel::where('tags', trim(ucwords(strtolower($name))))
                                       ->first()->toArray();
    }

    public function getAdd($request, $response)
    {
        return $this->view->render($response, 'admin/tag-add.twig');
    }

    public function postAdd($request, $response)
    {
        $tag = $request->getParsedBody();

        $this->validator->rule('required','tags');

        if($this->validator->validate()) {
            $tag = explode(",",ucwords(strtolower($tag['tags'])));
            foreach ($tag as $val) {
                $tag = TagModel::where('tags',trim($val))->first();

                if (is_null($tag)) {
                    TagModel::create(['tags'  => trim($val)]);
                } else {
                    $_SESSION['errors']['tags'] = "Tag ". trim($val) ." sudah ada";
                }
            }

            return $response->withRedirect($this->router->pathFor('tag.add'));
        } else {
            $_SESSION['errors']['tags'] = "Tag harus diisi";
            return $response->withRedirect($this->router->pathFor('tag.add'));
        }
    }

    public function getTags()
    {
        // var_dump(TagModel::orderBy('tags','asc')->get()->toArray());
        // die();
        return TagModel::orderBy('tags','asc')->get()->toArray();
    }

    public function getTagById($id)
    {
        return TagModel::where('id', $id)->first()->toArray();
    }

    public static function getTag($id)
    {
        $tag = TagModel::where('id', $id)->first();
        if (!is_null($tag)) {
            return $tag->toArray();
        } else {
            return NULL;
        }

    }

    public function delete($request, $response, $args)
    {
        $tag = TagModel::find($args['id']);
        $tag->delete;
    }
}
