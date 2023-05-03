<?php

namespace App\Models;


class UserModel
{
    private $id;
    private $name;
    private $password_hash;

    public function __construct($id, $name, $password_hash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password_hash = $password_hash;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPasswordHash()
    {
        return $this->password_hash;
    }
}