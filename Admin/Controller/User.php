<?php

namespace Admin\Controller;

use Admin\Model\User as UserModel;
use Application;

class User extends Base
{
    public function loginAction()
    {
        $post = $this->request->getParams();
        if ($this->request->getRequestMethod() == 'POST' && $postName = $post['login']) {
            $userModel = new UserModel();
            $user = $userModel->getUser();
            $userName = htmlspecialchars($user['login']);
            if (password_verify($post['password'], $user['password']) && $postName == $userName) {
                $this->session->setSession($userName);
                header("location:/admin");
            }
        }
        Application::render('/login.phtml', ['title' => 'authorization'], 'Admin');
    }

    public function logoutAction()
    {
        $this->session->desrtoySession();
        header("Location: /admin");
        exit;
    }
}