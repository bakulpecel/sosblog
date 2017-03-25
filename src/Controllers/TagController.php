<?php

namespace App\Controllers;

use App\Models\TagModel;

class TagController extends Controller
{

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
        return TagModel::orderBy('tags','asc')->get()->toArray();
    }

    public function getTagById($id)
    {
        return TagModel::where('id', $id)->first()->toArray();
    }

    public function delete($request, $response, $args)
    {
        $tag = TagModel::find($args['id']);
        $tag->delete;
    }
}
