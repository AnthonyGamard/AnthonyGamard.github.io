<?php

namespace App\Controllers;

use CPE\Framework\AbstractController;
use App\Models\CpdoModel;
use App\Models\Utils\JwtUtils;
use App\Models\Utils\Constantes;
use App\Controllers\AuthController;

class UserController extends AbstractController
{
  public function executeCreateUser()
  {
    $success = true;
    $messageIfError = "";
    $resData = null;

    $allHeaders = array_change_key_case(getallheaders(), CASE_LOWER);
    $authHeaderParts = AuthController::checkAuthHeader($allHeaders);

    $credentials = base64_decode($authHeaderParts[1]);
    $credentialsParts = explode(':', $credentials);
    $username = $credentialsParts[0];
    $password_hash = $credentialsParts[1];

    if (empty($password_hash) || empty($username)) {
      $success = false;
      $messageIfError = Constantes::MissingCredentials;
    } elseif(AuthController::checkIfUserAlreadyExists($username))
    {
      $success = false;
      $messageIfError = Constantes::UserExist;
    }
    else {
      $pdo = CpdoModel::GetInstance();
      $pdo->Query("INSERT INTO users (name, password_hash) VALUES ('$username', '$password_hash')");

      $credentials_valid = AuthController::checkCredentials($username, $password_hash);
      if (!$credentials_valid) {
        $success = false;
        $messageIfError = Constantes::IncorrectCredentials;
      } else {
        $resData = array("id" => $credentials_valid[0]['id'], "name" => $credentials_valid[0]['name']);
        $resData[] = array("token" => JwtUtils::newAccessToken($resData));
        unset($resData[0]['token']['expires']);
      }
    }

    $this->Response($success, $messageIfError, $resData);
  }

  public function executeConnexionUser()
  {
    $success = true;
    $messageIfError = "";
    $resData = null;

    $allHeaders = array_change_key_case(getallheaders(), CASE_LOWER);
    $authHeaderParts = AuthController::checkAuthHeader($allHeaders);

    $credentials = base64_decode($authHeaderParts[1]);
    $credentialsParts = explode(':', $credentials);
    $username = $credentialsParts[0];
    $password_hash = $credentialsParts[1];

    $credentials_valid = AuthController::checkCredentials($username, $password_hash);
    if (!$credentials_valid) {
      $success = false;
      $messageIfError = Constantes::IncorrectCredentials;
    } else {
      $resData = array("id" => $credentials_valid[0]['id'], "name" => $credentials_valid[0]['name']);
      $resData[] = array("token" => JwtUtils::newAccessToken($resData));
      unset($resData[0]['token']['expires']);
    }

    $this->Response($success, $messageIfError, $resData);
  }


  public function Response($success, $message, $data)
  {
    if ($success && $data) {
      http_response_code(200);
      $response = array("status" => "success", "data" => $data);
      header('Content-Type: application/json');
      echo json_encode($response);
      return;
    } else {
      http_response_code(400);
      $response = array("status" => "error", "message" => $message);
      header('Content-Type: application/json');
      echo json_encode($response);
      return;
    }
  }
}
