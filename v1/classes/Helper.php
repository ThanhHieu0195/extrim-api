<?php

class Helper {
    static public function randomString($str='') {
        if (empty($str)) {
            $str = rand();
        }
        return md5(uniqid($str, true));
    }

    static public function getCurrentUser() {
        $token = self::getTokenRequest();

        if ( !empty($token) ) {
            $user = new User();
            $user->getUserByToken($token, true);
            if ($user->isExist() ) {
                return $user;
            }
        }
        return false;
    }

    static public function checkPerfomance($role) {
        $token = self::getTokenRequest();

        if ( !empty($token) ) {
            $user = new User();
            $user->getUserByToken($token, true);
            if ($user->isExist() ) {
                return $user->isWho($role);
            }
        }
        return false;
    }

    static public function getTokenRequest() {
        if (isset($_SERVER['HTTP_TOKEN'])) {
            return $_SERVER['HTTP_TOKEN'];
        }
        return '';
    }
}