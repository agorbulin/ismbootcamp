<?php

class Session
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function setSession($userName)
    {
        $_SESSION['user'] = $userName;
    }

    public function setVisitorSession($session_id)
    {
        $_SESSION['visitor'] = $session_id;
    }


    public function setMessage($message)
    {
        $_SESSION['message'] = $message;
    }

    public function getMessage($clean = true)
    {
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            if ($clean) {
                unset($_SESSION['message']);
            }
            return $message;
        }
    }

    public function getSessionId()
    {
        return session_id();
    }

    public function issetSessionKey()
    {
        return isset($_SESSION['user']);
    }

    public function issetVisitorSession()
    {
        return isset($_SESSION['visitor']);
    }

    public function desrtoySession()
    {
        session_destroy();
    }

    public function refreshSessionId()
    {
        if (session_id()) {
            session_regenerate_id();
            unset($_SESSION['visitor']);
        } else {
            session_start();
        }
    }
}
