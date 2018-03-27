<?php

class User extends DB
{
    const TABLE = 'user';
    const isUser = 1; //user
    const isAdmin = 0; //user

    const TYPE = 0;
    const TYPE_FACEBOOK = 1;
    const TYPE_G_PLUS = 2;

    public $user = array();

    public function __get($name)
    {
        if (array_key_exists($name, $this->user)) {
            return $this->user->{$name};
        }
        return false;
    }

    public function getUserByUser($username, $password, $apply=false)
    {
        $username = $this->escape($username);
        $password = md5($password);
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE username='$username' and password='$password';";
        $user = $this->get_row($sql, true);
        if ( $apply ) {
            $this->user = $user;
        }
        return $user;
    }

    public function getUserByToken($token, $apply=false)
    {
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE token='$token';";
        $user = $this->get_row($sql, true);
        if ($apply) {
            $this->user = $user;
        }
        return $user;
    }

    public function hasUser($params) {
        return $this->exists(self::TABLE, 'id', $params);
    }

    public function getUser($params, $apply = false) {
        $params = $this->parseObjectToParams($params);
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE $params;";
        $user = $this->get_row($sql, true);

        if ($apply) {
            $this->user = $user;
        }

        return $user;
    }

    public function register($username, $fullname, $phone, $address, $sex, $password, $email, $type='', $token='')
    {
        if ($this->hasUser(array('username' => $username))) {
            return -1;
        }

        $username = $this->escape($username);
        if (empty($token)) {
            $token = $this->generateToken($username);
        }

        $date_created = time();
        $password = md5($password);
        $level = self::isUser;

        if ( !empty($type) ) {
            $type = self::TYPE;
        }
        if ($this->insert(self::TABLE,
            array(
                'username' => $username,
                'fullname' => $fullname,
                'phone' => $phone,
                'address' => $address,
                'sex' => $sex,
                'password' => $password,
                'token' => $token,
                'email' => $email,
                'date_created' => $date_created,
                'level' => $level,
                'type' => $type
            ))) {
            $this->getUserByToken($token, true);
            return $this->user->token;
        }
        return 0;
    }

    public function generateToken($username)
    {
        return Helper::randomString($username);
    }

    public function getToken()
    {
        return $this->user->token;
    }

    protected function setToken($token)
    {
        return $this->user->token = $token;
    }

    public function upDateToken()
    {
        return $this->update(self::TABLE, array('token' => $this->user->token), array('id' => $this->user->id));
    }

    public function save() {
        return $this->update(self::TABLE, $this->user, array('id' => $this->user->id));
    }

    public function isAdmin() {
        if ($this->user->level == self::isAdmin) {
            return true;
        }
        return false;
    }

    public function isUser() {
        if ($this->user->level == self::isUser) {
            return true;
        }
        return false;
    }

    public function isWho($role) {
        if ($this->level == $role) {
            return true;
        }
        return false;
    }

    public function isExist() {
        if (isset($this->user) && !empty($this->user) ) {
            return true;
        }
        return false;
    }

    public function getInfomation() {
        $user = $this->user;
        unset($user->id);
        unset($user->password);
        unset($user->level);
        unset($user->type);
        unset($user->token);
        return $user;
    }

    public function isExists() {
        if (!empty($this->user)) {
            return true;
        }
        return false;
    }
}