<?php

namespace App\Controllers;

use CPE\Framework\AbstractController;
use App\Models\CpdoModel;
use App\Models\Utils\JwtUtils;
use App\Models\Utils\Constantes;

class AuthController extends AbstractController
{
    public static function executeVerifyJWT()
    {
        $access_token = JwtUtils::getAccessTokenFromRequest();

        if (empty($access_token)) {
            http_response_code(400);
            $response = array("status" => "error", "message" => "Le token est manquant !");
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        } else {
            $resData = JwtUtils::checkAccessToken($access_token);
            http_response_code(200);
            $response = array("res" => $resData);
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    }

    public static function checkIfUserAlreadyExists($username)
    {
        $pdo = CpdoModel::GetInstance();
        $resData = $pdo->getTabDataFromSql("SELECT * FROM users WHERE name ='$username';");
        if ($resData) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkCredentials($username, $password)
    {
        $pdo = CpdoModel::GetInstance();
        $resData = $pdo->getTabDataFromSql("SELECT * FROM users WHERE name ='$username' AND password_hash = '$password';");
        if (empty($resData))
            return null;
        else
            return $resData;
    }

    public static function checkAuthHeader($allHeaders)
    {
        if (!isset($allHeaders['authorization'])) {
            http_response_code(401);
            echo Constantes::AuthHeaderMissing;
            exit;
        }

        $authHeader = $allHeaders['authorization'];
        $authHeaderParts = explode(' ', $authHeader);

        // Check if the authentication type is Basic
        if ($authHeaderParts[0] !== 'Basic') {
            http_response_code(401);
            echo Constantes::InvalidAuthType;
            exit;
        }

        // Check if the credentials are provided
        if (!isset($authHeaderParts[1])) {
            http_response_code(401);
            echo Constantes::MissingCredentials;
            exit;
        }

        return $authHeaderParts;
    }
}
