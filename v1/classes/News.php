<?php

class News extends DB
{
    const TABLE = 'news';
    const SPECIAL_SHOWHOME = 1;
    public $new;

    public function __construct()
    {
        parent::__construct();
    }

    public function getNewBySpecial($special, $limit='', $offset='') {
        if ( empty($limit) ) {
            $limit= Constants::NUMSERVICE;
        }

        if ( empty($offset) ) {
            $offset = 0;
        }

        $sql = "SELECT n.*, a.dir FROM `" . self::TABLE . "` n inner join `attachment` a on n.attachment=a.id  WHERE special='$special' limit {$offset}, {$limit};";
        $result = $this->get_results($sql, true);
        return $result;
    }

    public function getNew($id) {
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE id='$id';";
        $this->new = $this->get_row($sql, true);
        return $this->new;
    }

    public function getAllNew() {
        $limit = Constants::NUMSERVICE;
        $table = self::TABLE;
        $sql = "SELECT * FROM $table LIMIT $limit;";
        $results = $this->get_results($sql, true);
        return $results;
    }

    public function create($title, $description, $special, $attachment) {
        $date_created = time();
        if ( empty($attachment) ) {
            $attachment = -1;
        }

        $result = $this->insert(self::TABLE, array(
            'title' => $title,
            'description' => $description,
            'special' => $special,
            'attachment' => $attachment,
            'date_created' => $date_created
        ));

        if ($result) {
            return $this->lastid();
        }
        return '';
    }

    public function isExists() {
        if (!empty($this->new)) {
            return true;
        }
        return false;
    }

    public function save() {
        return $this->update(self::TABLE, $this->new, array('id' => $this->id));
    }

    public function remove() {
        return $this->delete(self::TABLE, array('id' => $this->id));
    }

    public function __get($name)
    {
        if ( array_key_exists($name, $this->new)) {
            return $this->new->{$name};
        }
        return '';
    }

    public function __isset($name)
    {
        if ( array_key_exists($name, $this->new)) {
            return true;
        }
        return false;
    }

    public function __set($name, $value)
    {
        if (isset($this->{$name})) {
            $this->new->{$name} = $value;
        }
    }
}