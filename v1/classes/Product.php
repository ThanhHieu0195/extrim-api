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
        $limit = Constants::NUMSERVICE;
        $table = self::TABLE;
        $sql = "SELECT * FROM $table LIMIT $limit;";
        $results = $this->get_results($sql, true);
        return $results;
    }

    public function create($title, $description, $content, $price, $author, $producer, $attachment) {
        $date_created = time();
        if ( empty($attachment) ) {
            $attachment = -1;
        }

        $result = $this->insert(self::TABLE, array(
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'price' => $price,
            'author' => $author,
            'producer' => $producer,
            'attachment' => $attachment,
            'date_created' => $date_created
        ));

        if ($result) {
            return $this->lastid();
        }
        return '';
    }

    public function isExists() {
        if (!empty($this->product)) {
            return true;
        }
        return false;
    }

    public function save() {
        return $this->update(self::TABLE, $this->product, array('id' => $this->id));
    }

    public function remove() {
        return $this->delete(self::TABLE, array('id' => $this->id));
    }

    public function __get($name)
    {
        if ( array_key_exists($name, $this->product)) {
            return $this->product->{$name};
        }
        return '';
    }

    public function __isset($name)
    {
        if ( array_key_exists($name, $this->product)) {
            return true;
        }
        return false;
    }

    public function __set($name, $value)
    {
        if (isset($this->{$name})) {
            $this->product->{$name} = $value;
        }
    }
}