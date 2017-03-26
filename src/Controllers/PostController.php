<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\UserModel;
use App\Controllers\UserController;
use App\Controllers\CommentController;

/**
* 
*/
class PostController extends Controller
{
	/**
	* 
	*/
	public function getAdd($request, $response)
	{
		return $this->view->render($response, 'admin/post-add.twig');
	}
	
	/**
	*
	*/
	public function postAdd($request, $response)
	{
		$request = $request->getParsedBody();

		$rules = [
			'required' => [
				['title'],
				['content']
			],
			'lengthMin' => [
				['title', 5],
				['content', 50]
			],
			'lengthMax' => [
				['title', 50],
				['content', 50000]
			],
		];

		$this->validator->rules($rules);

		if ($this->validator->validate()) {
			$article = new PostModel;
			$article->title 	= $request['title'];
			$article->content 	= $request['content'];
			$article->user_id	= $_SESSION['login']['id'];
			$article->save();

			return $response->withRedirect($this->router->pathFor('post.list'));
		} else {
			$_SESSION['errors'] = $this->validator->errors();
			$_SESSION['old']	= $request;

			return $response->withRedirect($this->router->pathFor('post.add'));
		}
	}

	/**
	*
	*/
	public static function getList()
	{
		return PostModel::orderBy('id', 'DESC')->where('deleted', 0)->get();
	}

	/**
	*
	*/
	public static function getPostUser($args)
	{
		return PostModel::orderBy('created_at', 'DESC')->where('deleted', 0)->where('user_id', $args)->get();
	}

	/**
	*
	*/
	public function getListByUser($request, $response, $args)
	{
		$article = self::getPostUser($args['id']);

		return $this->view->render($response , 'blog/post-list.twig', ['article' => $article]);
	}

	/**
	*
	*/
	public function getPostByUser($request, $response, $args)
	{
		$article = self::getPostUser($args['id'])->where();

		return $this->view->render($response, 'blog/post-list.twig', ['article' => $article]);
	}


	/**
	*
	*/
	public function getListFrontBlog($request, $response)
	{
		$article = self::getList();

		return $this->view->render($response , 'blog/post-list.twig', ['article' => $article]);
	} 

	/**
	*
	*/
	public function getRead($request, $response, $args)
	{
		$article = PostModel::find($args['id']);

		$comment = CommentController::getByPost($args['id']);

		return $this->view->render($response, 'blog/post-read.twig', ['article' => $article, 'comment' => $comment]);
	}

	/**
	*
	*/
	public function getListAdmin($request, $response)
	{	
		if ($_SESSION['login']['username'] == 'admin') {
			$article = PostModel::orderBy('id', 'DESC')->where('deleted', 0)->get();
			return $this->view->render($response, 'admin/post-list.twig', ['article' => $article]);
		} else {
			$article = PostModel::orderBy('id', 'DESC')->where('user_id', $_SESSION['login']['id'])->where('deleted', 0)->get();
			return $this->view->render($response, 'admin/post-list.twig', ['article' => $article]);
		}
	}

	/**
	*
	*/
	public function getEdit($request, $response, $args)
	{
		$article = PostModel::where('id', $args['id'])->first();

		return $this->view->render($response, 'admin/post-edit.twig', ['article' => $article]);
	}

	/**
	*
	*/
	public function postEdit($request, $response, $args)
	{
		$request = $request->getParsedBody();

		$rules = [
			'required' => [
				['title'],
				['content']
			],
			'lengthMin' => [
				['title', 5],
				['content', 50]
			],
			'lengthMax' => [
				['title', 50],
				['content', 5000]
			],
		];

		$this->validator->rules($rules);

		if ($this->validator->validate()) {
			$article = PostModel::where('id', $args['id'])->first();
			$article->title 	= $request['title'];
			$article->content 	= $request['content'];
			$article->update();

			return $response->withRedirect($this->router->pathFor('post.list'));
		} else {
			$_SESSION['errors'] = $this->validator->errors();
			$_SESSION['old']	= $request;

			return $response->withRedirect($this->router->pathFor('post.edit', ['id' => $args['id']]));
		}
	}

	/**
	*
	*/
	public function getTrashList($request, $response)
	{
		$article = PostModel::orderBy('id', 'DESC')->where('deleted', 1)->get();

		return $this->view->render($response, 'admin/post-trash.twig', ['article' => $article]);
	}

	/**
	*
	*/
	public function setSoftdDelete($request, $response, $args)
	{
		$article = PostModel::find($args['id']);
		$article->deleted = 1;
		$article->update();

		return $response->withRedirect($this->router->pathFor('post.list'));
	}

	/**
	*
	*/
	public function setHardDelete($request, $response, $args)
	{
		$article = PostModel::find($args['id']);
		$article->delete();

		return $response->withRedirect($this->router->pathFor('post.trash'));
	}


	/**
	*
	*/
	public function setRestore($request, $response, $args)
	{
		$article = PostModel::find($args['id']);
		$article->deleted = 0;
		$article->update();

		return $response->withRedirect($this->router->pathFor('post.trash'));
	}


	/**
	*
	*/
	public function getSearch($request, $response)
	{
		$request = $request->getParsedBody();

		$article = PostModel::where('title', 'LIKE', '%' . $request['search'] . '%')->orWhere('content', 'LIKE', '%' . $request['search'] . '%')->get();

		return $this->view->render($response , 'blog/post-list.twig', ['article' => $article]);
	}
}