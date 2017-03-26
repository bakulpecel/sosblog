<?php

namespace App\Controllers;

use App\Models\CommentModel;

/**
* 
*/
class CommentController extends Controller
{
	/**
    *
    */
    public function postComment($request, $response, $args)
    {
        if (!$_SESSION['login']) {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

    	$request = $request->getParsedBody();

    	$rules = [
    		'required' => [
    			['comment']
    		],
    	];
    	$this->validator->rules($rules);
    	if ($this->validator->validate()) {
    		$comment = new CommentModel;
	        $comment->comment = $request['comment'];
	        $comment->post_id = $args['id'];
	        $comment->user_id = $_SESSION['login']['id'];
	        $comment->save();

	        return $response->withRedirect($this->router->pathFor('post.read', ['id' => $args['id']]));
    	} else {
    		$_SESSION['errors']	= $this->validator->errors();
    		$_SESSION['old']	= $request;
    		
    		return $response->withRedirect($this->router->pathFor('post.read', ['id' => $args['id']]));
    	}
        
    }

    /**
    *
    */
    public static function getByPost($args)
    {
    	return CommentModel::where('post_id', $args)->where('deleted', 0)->get();
    }

    /**
    *
    */
    public function getListCommentAdmin($request, $response)
    {
    	if ($_SESSION['login']['username'] == 'admin'){
    		$comment = CommentModel::orderBy('id', 'DESC')->where('deleted', 0)->get();
    		return $this->view->render($response, 'admin/comment-list.twig', ['comment' => $comment]);
    	} else {
    		$comment = CommentModel::orderBy('id', 'DESC')->where('user_id', $_SESSION['login']['id'])->where('deleted', 0)->get();
    		return $this->view->render($response, 'admin/comment-list.twig', ['comment' => $comment]);
    	}
    }

}