<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
*
*/
class UserController extends Controller
{
    /**
    *
    */
    public function getAdd($request, $response)
    {
        return $this->view->render($response, 'admin/user-add.twig');
    }

    /**
    *
    */
    public function postAdd($request, $response)
    {
        $request = $request->getParsedBody();

		$this->validator->rule('required', ['username', 'email', 'password'])
                        ->rule('email','email');

		if ($this->validator->validate()) {
            $user = UserModel::where('username', $request['username'])->first();

            if (is_null($user['username'])) {
                $email = UserModel::where('email', $request['email'])->first();

                if (is_null($email['email'])) {
                    UserModel::create([
                        'username'  => $request['username'],
                        'email'     => $request['email'],
                        'password'  => md5($request['password']),
                        'role_id'   => 1,
                    ]);

        			return $response->withRedirect($this->router->pathFor('user.list'));

                } else {
                    $_SESSION['errors'] = 'email sudah digunakan. ganti lainnya';
        			$_SESSION['old']	= $request;

        			return $response->withRedirect($this->router->pathFor('user.add'));
                }
            } else {
                $_SESSION['errors'] = 'username sudah digunakan. ganti lainnya';
    			$_SESSION['old']	= $request;

                // return var_dump($_SESSION['errors']);
    			return $response->withRedirect($this->router->pathFor('user.add'));
            }
            //

		} else {
			$_SESSION['errors'] = $this->validator->errors();
			$_SESSION['old']	= $request;

			return $response->withRedirect($this->router->pathFor('user.add'));
		}
    }

    /**
    *
    */
    public function getEdit($request, $response, $args)
    {
        $user = UserModel::where('id', $args['id'])->first();
        return $this->view->render($response, 'admin/user-edit.twig', ['user' => $user]);
        // return var_dump($user);
    }
    public function postEdit($request, $response, $args)
    {
        $request = $request->getParsedBody();

        $this->validator->rule('required' , ['username','email'])
                        ->rule('email', 'email');
        if ($this->validator->validate()) {
            if (empty($request['old-password']) && empty($request['new-password'])) {
                $user = UserModel::where('id', $args['id'])->first();
                $cekuser = UserModel::where('username', $request['username'])->first();
                $cekemail = UserModel::where('email', $request['email'])->first();

                if ($user['username'] === $request['username'] && $user['email'] === $request['email']) {
                    // $user->email    = $request['email'];
                    // $user->update();

                    return $response->withRedirect($this->router->pathFor('user.list'));
                } elseif ($user['username'] === $request['username'] && $user['email'] !== $request['email']) {
                    if (is_null($cekemail['email'])) {
                        $user->email    = $request['email'];
                        $user->update();

                        return $response->withRedirect($this->router->pathFor('user.list'));
                    } else {
                        $_SESSION['errors'] = 'email sudah digunakan. ganti lainnya';
                        $_SESSION['old']    = $request;

                        return $response->withRedirect($this->router->pathFor('user.edit', $args));
                    }
                } elseif ($user['username'] !== $request['username'] && $user['email'] === $request['email']) {
                    if (is_null($cekuser['username'])) {
                        $user->username    = $request['username'];
                        $user->update();

                        return $response->withRedirect($this->router->pathFor('user.list'));
                    } else {
                        $_SESSION['errors'] = 'username sudah digunakan. ganti lainnya';
                        $_SESSION['old']    = $request;

                        return $response->withRedirect($this->router->pathFor('user.edit', $args));
                    }
                } else {
                    if (is_null($cekuser['username'])) {

                        if (is_null($cekemail['email'])) {
                            $user->username = $request['username'];
                            $user->email    = $request['email'];
                            $user->update();

                            return $response->withRedirect($this->router->pathFor('user.list'));
                        } else {
                            $_SESSION['errors'] = 'email sudah digunakan. ganti lainnya';
                            $_SESSION['old']    = $request;

                            return $response->withRedirect($this->router->pathFor('user.edit', $args));
                        }
                    } else {
                        $_SESSION['errors'] = 'username sudah digunakan. ganti lainnya';
                        $_SESSION['old']    = $request;

                        return $response->withRedirect($this->router->pathFor('user.edit', $args));
                    }
                }
            } else {
                if (empty($request['old-password'])) {
                    $_SESSION['errors'] = "password lama harus diisi";
                    $_SESSION['old']    = $request;

                    return $response->withRedirect($this->router->pathFor('user.edit', $args));
                } elseif (empty($request['new-password'])) {
                    $_SESSION['errors'] = "password baru harus diisi";
                    $_SESSION['old']    = $request;

                    return $response->withRedirect($this->router->pathFor('user.edit', $args));
                } else {
                    $pass       = UserModel::where('id', $args['id'])->first()['password'];
                    $passenter  = md5($request['old-password']);

                    if ($passenter == $pass) {
                        $this->validator->rule('required' , ['username','email', 'new-password'])
                                        ->rule('email', 'email');
                        if ($this->validator->validate()) {
                            $user = UserModel::where('id', $args['id'])->first();
                            $user->username = $request['username'];
                            $user->email    = $request['email'];
                            $user->password = md5($request['new-password']);
                            $user->update();

                            return $response->withRedirect($this->router->pathFor('user.list'));
                        } else {
                            $_SESSION['errors'] = $this->validator->errors();
                            $_SESSION['old']    = $request;

                            return $response->withRedirect($this->router->pathFor('user.edit', $args));
                        }

                    } else {
                        $_SESSION['errors'] = 'password anda salah';
                        $_SESSION['old']    = $request;


                        return $response->withRedirect($this->router->pathFor('user.edit', $args));
                    }
                }
            }
        }

    }

    /**
    *
    */
    public function getList($request, $response)
    {
        $user = UserModel::orderBy('id', 'DESC')->where('deleted', 0)->get();

        return $this->view->render($response, 'admin/user-list.twig', ['user' => $user]);
    }

    public function getTrash($request, $response)
    {
        $user = UserModel::orderBy('id', 'DESC')->where('deleted', 1)->get();

        return $this->view->render($response, 'admin/user-trash.twig', ['user' => $user]);
    }

    public function softDelete($request, $response, $args)
    {
        $user = UserModel::where('id', $args['id'])->first();
        $user->deleted = 1;
        $user->update();

        return $response->withRedirect($this->router->pathFor('user.list'));
    }
    public function delete($request, $response, $args)
    {
        $user = UserModel::find($args['id']);
        $user->delete();

        return $response->withRedirect($this->router->pathFor('user.trash'));
    }

    public function restore($request, $response, $args)
    {
        $user = UserModel::where('id', $args['id'])->first();
        $user->deleted = 0;
        $user->update();

        return $response->withRedirect($this->router->pathFor('user.trash'));
    }

    /**
    *
    */
    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    /**
    *
    */
    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    /**
    *
    */
    public function postSignIn($request, $response)
    {

    }

    /**
    *
    */
    public function postSignUp($request, $response)
    {
        $request = $request->getParsedBody();

        $v = $this->validator;
        $v->rule('required', ['username', 'email', 'password']);
        $v->rule('alphaNum', 'username');
        $v->rule('email', 'email');
        $v->rule('lengthMin', ['password', 6]);

        if ($this->validator->validate()) {
            $signUp = new UserModel;
            $signUp->username   = $request['username'];
            $signUp->email      = $request['email'];
            $signUp->password   = password_hash($request['password'], PASSWORD_DEFAULT);
            $signUp->save();
        } else {

        }

    }
}
