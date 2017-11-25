<?php

class SessionController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    private function _registerSession($user)
    {
        $this->session->set(
            "auth",
            [
                "id"   => $user->id,
                "login" => $user->login,
            ]
        );
    }

    public function logoutAction() {
        $this->session->remove("auth");
        return $this->dispatcher->forward(
            [
                "controller" => "index",
                "action"     => "index",
            ]
        );

    }

    public function startAction()
    {
        if ($this->request->isPost()) {
            // Get the data from the user
            $login    = $this->request->getPost("login");
            $password = $this->request->getPost("password");

            // Find the user in the database
            $user = Users::findFirst(
                [
                    "(login = :login:) AND password = :password:",
                    "bind" => [
                        "login"    => $login,
                        "password" => $password,
                    ]
                ]
            );

            if ($user !== false) {
                $this->_registerSession($user);

                $this->flash->success(
                    "Welcome " . $user->getLogin()
                );

                // Forward to the 'invoices' controller if the user is valid
                return $this->dispatcher->forward(
                    [
                        "controller" => "index",
                        "action"     => "index",
                    ]
                );
            }

            $this->flash->error(
                "Wrong login/password"
            );
        }

        // Forward to the login form again
        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }

}
