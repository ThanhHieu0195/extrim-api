<?php

class Product extends DB
{
    const TABLE = 'product';
    public $product;

    public function __construct()
    {
        parent::__construct();
    }

    public function getProduct($id) {
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE id='$id';";
        $this->product = $this->get_row($sql, true);
        return $this->product;
    }

    public function getAllProduct() {
        $limit = Constants::NUMPRODUCT;
        $table = self::TABLE;
        $sql = "SELECT * FROM $table LIMIT $limit;";
        $results = $this->get_results($sql, true);
        return $results;
    }
}