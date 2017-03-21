<?php

namespace App\Auth;

use App\Models\UserModel;

class auth
{
    public function isUser()
    {
        return UserModel::where('username', $_SESSION['login']['username'])->first()->toArray();
        // return UserModel::find($user['']);
    }
    public function isAdmin()
    {
        $user = $this->isUser();

        if (is_null($user)) {
            return false;
        } else {
            if ($user['role_id'] === 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function check()
    {
        if (!is_null($_SESSION['login'])) {
            return true;
        } else {
            return false;
        }
    }

    public function attempt($username, $password)
    {
        $user = UserModel::where('username', $username)->first();
        // var_dump($user->toArray());

        if (is_null($user)) {
            $_SESSION['errors']['username'] = "Username salah";
            return false;
        } else {
            if (password_verify($password, $user->password)) {
                // $user = unserialize(serialize($user));
                $_SESSION['login'] = $user->toArray();

                return true;
            } else {
                $_SESSION['errors']['password'] = "Password salah";
                return false;
            }
        }
    }

    public function signOut()
    {
        unset($_SESSION['login']);
    }
}
