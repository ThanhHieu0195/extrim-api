<?php

class Helper {
    static public function randomString($str='') {
        if (empty($str)) {
            $str = rand();
        }
        return md5(uniqid($str, true));
    }
}