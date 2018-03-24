<?php

class Service extends DB
{
    const TABLE = 'service';
    public $service;

    public function __construct()
    {
        parent::__construct();
    }

    public function getService($id) {
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE id='$id';";
        $this->service = $this->get_row($sql, true);
        return $this->service;
    }

    public function getAllService() {
        $limit = Constants::NUMSERVICE;
        $table = self::TABLE;
        $sql = "SELECT * FROM $table LIMIT $limit;";
        $results = $this->get_results($sql, true);
        return $results;
    }

    public function create($title, $description, $price, $attachment) {
        $date_created = time();
        if ( empty($attachment) ) {
            $attachment = -1;
        }

        $result = $this->insert(self::TABLE, array(
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'attachment' => $attachment,
            'date_created' => $date_created
        ));

        if ($result) {
            return $this->lastid();
        }
        return '';
    }

    public function isExists() {
        if (!empty($this->service)) {
            return true;
        }
        return false;
    }

    public function save() {
        return $this->update(self::TABLE, $this->service, array('id' => $this->id));
    }

    public function remove() {
        return $this->delete(self::TABLE, array('id' => $this->id));
    }

    public function __get($name)
    {
        if ( array_key_exists($name, $this->service)) {
            return $this->service->{$name};
        }
        return '';
    }

    public function __isset($name)
    {
        if ( array_key_exists($name, $this->service)) {
            return true;
        }
        return false;
    }

    public function __set($name, $value)
    {
        if (isset($this->{$name})) {
            $this->service->{$name} = $value;
        }
    }
}