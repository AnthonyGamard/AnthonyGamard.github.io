<?php

namespace App\Controllers;

use CPE\Framework\AbstractController;
use App\Models\CpdoModel;

class NavireController extends AbstractController
{
    public function executeGetAllShips()
    {
        $pdo = CpdoModel::GetInstance();
        $resData = $pdo->getTabDataFromSql("SELECT * FROM navire;");

        http_response_code(200);
        $response = array("status" => "success", "data" => $resData);
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }
}
