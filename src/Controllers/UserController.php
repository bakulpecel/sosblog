<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Auth\Auth;

/**
*
*/
class UserController extends Controller
{
    public function checkAdmin()
    {
        $auth = $this->auth;

        if($auth->check()) {

            if ($auth->isAdmin()) {
                return true;
            } else {
                $_SESSION['errors']['login'] = "Maaf anda tidak memiliki akses";
                return false;
                // return $response->withRedirect($this->router->pathFor('auth.signin'));
            }
        } else {
            $_SESSION['errors']['login'] = "Anda harus login terlebih dahulu";
            return false;
            // return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

    }

    public function checkUser()
    {
        $auth = $this->auth;

        if ($auth->check()) {
            if ($auth->isUser()) {
                return true;
            } else {
                $_SESSION['errors']['login'] = "Maaf user yang anda gunakan salah";
                return false;
                // return $response->withRedirect($this->router->pathFor('auth.signin'));
            }
        } else {
            $_SESSION['errors']['login'] = "Anda harus login terlebih dahulu";
            return false;
            // return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
    }

    /**
    *
    */
    public function getAdd($request, $response)
    {
        if ($this->checkUser()) {
            if ($this->checkAdmin()) {
                return $this->view->render($response, 'admin/user-add.twig');
            } else {
                return $response->withRedirect($this->router->pathFor('post.list'));
            }
        } else {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

    }

    /**
    *
    */
    public function postAdd($request, $response)
    {
        $request = $request->getParsedBody();

        $rules = [
            'required' => [
                ['username'],
                ['email'],
                ['password'],
            ],
            'email' => [['email']],
            'lengthMin' => [
				['username', 6],
				['email', 10]
			],
			'lengthMax' => [
				['username', 12],
				['email', 50]
			],
        ];
        $this->validator->rules($rules);

		if ($this->validator->validate()) {
            $user = UserModel::where('username', $request['username'])->first();

            if (is_null($user['username'])) {
                $email = UserModel::where('email', $request['email'])->first();

                if (is_null($email['email'])) {
                    UserModel::create([
                        'username'  => $request['username'],
                        'email'     => $request['email'],
                        'password'  => password_hash($request['password'], PASSWORD_DEFAULT),
                        'role_id'   => 2,
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
        if ($this->checkAdmin()) {
            $user = UserModel::where('id', $args['id'])->first();
            return $this->view->render($response, 'admin/user-edit.twig', ['user' => $user]);
        } else {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        // return var_dump($user);
    }
    public function postEdit($request, $response, $args)
    {
        $request = $request->getParsedBody();


        $rules = [
            'required' => [
                ['username'],
                ['email'],
            ],
            'email' => [
                ['email']
            ],
            'lengthMin' => [
				['username', 6],
				['email', 10],
			],
        ];

        $this->validator->rules($rules);

        if ($this->validator->validate()) {
            if (empty($request['new-password'])) {
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
                if (empty($request['new-password'])) {
                    $_SESSION['errors'] = "password baru harus diisi";
                    $_SESSION['old']    = $request;

                    return $response->withRedirect($this->router->pathFor('user.edit', $args));
                } else {
                    $this->validator->rule('required' , ['username','email', 'new-password'])
                                    ->rule('email', 'email')
                                    ->rule('lengthMin', ['password',6]);
                    if ($this->validator->validate()) {
                        $user = UserModel::where('id', $args['id'])->first();
                        $user->username = $request['username'];
                        $user->email    = $request['email'];
                        $user->password = password_hash($request['new-password'], PASSWORD_DEFAULT);
                        $user->update();

                        return $response->withRedirect($this->router->pathFor('user.list'));
                    } else {
                        $_SESSION['errors'] = $this->validator->errors();
                        $_SESSION['old']    = $request;

                        return $response->withRedirect($this->router->pathFor('user.edit', $args));
                    }
                }
            }
        } else {
            $_SESSION['errors'] = $this->validator->errors();
            $_SESSION['old']    = $request;

            return $response->withRedirect($this->router->pathFor('user.edit', $args));
        }

    }

    /**
    *
    */
    public function getList($request, $response)
    {
        if ($this->checkAdmin()) {
            $user = UserModel::orderBy('id', 'DESC')->where('deleted', 0)->get();

            return $this->view->render($response, 'admin/user-list.twig', ['user' => $user]);
        } else {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
        // var_dump($_SESSION['login']);
    }

    public function getTrash($request, $response)
    {
        if ($this->checkAdmin()) {
            $user = UserModel::orderBy('id', 'DESC')->where('deleted', 1)->get();

            return $this->view->render($response, 'admin/user-trash.twig', ['user' => $user]);
        } else {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

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




// Auth =======================================================================
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
    public function postSignUp($request, $response)
    {
        $request = $request->getParsedBody();

        $rules = [
            'required'  => [
                ['username'],
                ['email'],
                ['password'],
            ],
            'email'     => [['email']],
            'lengthMin' => [
                ['username', 6],
                ['password', 6]
            ],
        ];

        $this->validator->rules($rules);

        if ($this->validator->validate()) {
            $cekuser = UserModel::where('username', $request['username'])->first();

            if (is_null($cekuser)) {
                $cekemail = UserModel::where('email', $request['email'])->first();
                if (is_null($cekemail)) {
                    $signUp = new UserModel;

                    $signUp->username   = $request['username'];
                    $signUp->email      = $request['email'];
                    $signUp->password   = password_hash($request['password'], PASSWORD_DEFAULT);
                    $signUp->role_id    = 2;
                    $signUp->save();

                    return $response->withRedirect($this->router->pathFor('auth.signin'));
                } else {
                    $_SESSION['errors']['email'] = ['email sudah digunakan ganti lainnya'];
                    $_SESSION['old']    = $request;

                    return $response->withRedirect($this->router->pathFor('auth.signup', $request));
                }
            } else {
                $_SESSION['errors']['username'] = 'username sudah digunakan ganti lainnya';
                $_SESSION['old']    = $request;

                return $response->withRedirect($this->router->pathFor('auth.signup', $request));
            }
        } else {
            $_SESSION['errors'] = $this->validator->errors();
            $_SESSION['old']    = $request;

            return $response->withRedirect($this->router->pathFor('auth.signup', $request));
        }



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
        $request = $request->getParsedBody();

        $user = $this->auth->attempt($request['username'], $request['password']);

        if($user) {
            // return var_dump($_SESSION['login']);
            if ($this->checkUser()) {
                if ($this->checkAdmin()) {
                    return $response->withRedirect($this->router->pathFor('user.list'));
                } else {
                    return   $response->withRedirect($this->router->pathFor('post.list'));
                }
            } else {
                $this->flash->addMessage('error', ' ');
                return $response->withRedirect($this->router->pathFor('auth.signin'));
            }
        } else {
            $this->flash->addMessage('error', ' ');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
    }


    public function getSignOut($request, $response)
    {
        if (empty($_SESSION['login'])) {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        } else {
            $this->auth->signOut();
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
    }

    public static function getUsername($id)
    {
        $user = UserModel::where('id', $id)->first();
        return $user->username();
    }


}
