<?php

class Service extends DB
{
    const TABLE = 'service';
    public $service;

    public function __construct()
    {
        parent::__construct();
    }

    public function getProduct($id) {
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE id='$id';";
        $this->product = $this->get_row($sql, true);
        return $this->service;
    }

    public function getAllProduct() {
        $limit = Constants::NUMSERVICE;
        $table = self::TABLE;
        $sql = "SELECT * FROM $table LIMIT $limit;";
        $results = $this->get_results($sql, true);
        return $results;
    }

    public function create($title, $price, $attachment) {
        $date_created = time();
        $result = $this->insert(self::TABLE, array(
            'title' => $title,
            'price' => $price,
            'attachment' => $attachment,
            'date_created' => $date_created
        ));

        if ($result) {
            return $this->lastid();
        }
        return '';
    }
}