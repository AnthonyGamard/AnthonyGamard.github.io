<?php

namespace App\Models;

use PDO;
use PDOException;


class CpdoModel
{
    private static $Instance = null;

    private function getPDO()
    {
        $pdo = null;
        try {
            $pdo = new PDO('sqlite:Data/battleship.db');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }

        return $pdo;
    }

    public function getTabDataFromSql($squery)
    {
        $pdo = $this->getPDO();
        $res = $pdo->prepare($squery);
        $res->execute();
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function Query($squery)
    {
        $pdo = $this->getPDO();
        $pdo->query($squery);
    }

    // pattern singleton
    public static function GetInstance()
    {
        if (self::$Instance == null) {
            self::$Instance = new CpdoModel();
            return self::$Instance;
        }

        return self::$Instance;
    }
}
