<?php

class Attachment extends DB
{
    const TABLE = 'attachment';
    const ARR_TYPE_ALLOW = array(
        'image/jpeg',
        'image/png'
    );

    public $attachment;
    public $file;


    public function __construct()
    {
        parent::__construct();
    }


    public function getAttachmentById($id) {
        return $this->getAttachment(array('id' => $id) , true);
    }

    public function getAttachment($params, $apply = false) {
        $params = $this->parseObjectToParams($params);
        $sql = "SELECT * FROM `" . self::TABLE . "` WHERE $params;";
        $attachment = $this->get_row($sql, true);

        if ($apply) {
            $this->attachment = $attachment;
        }

        return $attachment;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->file) ) {
            return $this->attachment->{$name};
        }
        return '';
    }

    public function getUrl() {
        return Constants::HOME_URL . '/' . $this->attachment->dir;
    }

    public function upload($file) {
        try {
            $message = 'Upload not success';
            $tmp_name = $file['tmp_name'];
            $namefile = $file['name'];
            $dir = Constants::DIR_UPLOAD . '/' . $namefile;

            if (file_exists($dir)) {
                $dir = Constants::DIR_UPLOAD . '/' . time() . $namefile;
            }

            if ( !in_array($file['type'], self::ARR_TYPE_ALLOW) ) {
                throw new Exception('Type not support!');
            }
            if (move_uploaded_file($tmp_name, $dir)) {
                $params = array(
                    'name' => $namefile,
                    'type' => $file['type'],
                    'size' => $file['size'],
                    'dir' => $dir
                );

                $result = $this->create($params);

                if ($result) {
                    return array(
                        'Error' => false,
                        'id' => $result
                    );
                }
            }
            return array(
                'Error' => true,
                'message' => $message
            );
        } catch (Exception $e) {
            return array(
                'Error' => true,
                'message' => $e->getMessage()
            );
        }
    }

    public function create($params) {
        if ($this->insert(self::TABLE, $params)) {
            return $this->lastid();
        }
        return '';
    }
}