<?php

namespace App\Controllers;

use CPE\Framework\AbstractController;

class GameController extends AbstractController {

    public function executeGetGameById() {
        $success = true;
        $messageIfError = "";
        $resData = null;

        $reqData = json_decode(file_get_contents('php://input'), true);
        $idGame = intval($reqData['idGame']);

        if (empty($idGame)) {
            $success = false;
            $messageIfError = "Veuillez fournir un identifiant de jeu.";
        } else {
            $pdo = Cpdo::GetInstance();
            $resData = $pdo->getTabDataFromSql("SELECT * FROM game WHERE idGame = $idGame");
        }

        $this->ResultSelectQuery($success, $messageIfError, $resData);
    }

    public function executeCreateGame() {
        $success = true;
        $messageIfError = "";
        $resData = null;

        $reqData = json_decode(file_get_contents('php://input'), true);
        $playerId = intval($reqData['playerId']);

        if (empty($playerId)) {
            $success = false;
            $messageIfError = "Veuillez fournir un identifiant de joueur.";
        } else {
            $pdo = Cpdo::GetInstance();
            $pdo->Query("INSERT INTO game (player1_id) VALUES ('$playerId')");
            $resData = $pdo->getTabDataFromSql("SELECT id FROM game WHERE player1_id = '$playerId' AND player2_id IS NULL");
        }

        $this->ResultSelectQuery($success, $messageIfError, $resData);
    }

    public function executeJoinGame() {
        $success = true;
        $messageIfError = "";
        $resData = null;

        $reqData = json_decode(file_get_contents('php://input'), true);
        $playerId = intval($reqData['playerId']);
        $idGame = intval($reqData['idGame']);

        if (empty($playerId) || empty($idGame)) {
            $success = false;
            $messageIfError = "Veuillez fournir un identifiant de joueur et un identifiant de jeu.";
        } else {
            $pdo = Cpdo::GetInstance();
            $pdo->Query("UPDATE game SET player2_id = '$playerId' WHERE id = '$idGame'");
            $resData = $pdo->getTabDataFromSql("SELECT * FROM game WHERE player2_id = '$playerId' AND id = '$idGame'");
        }

        $this->ResultSelectQuery($success, $messageIfError, $resData);
    }

    public function executePlay() {
        // Récupération des données de la requête
        $reqData = json_decode(file_get_contents('php://input'), true);
        $idGame = intval($reqData['idGame']);
        $move = $reqData['move'];
        $playerName = $reqData['playerName'];
    
        // Vérification des données de la requête
        if (empty($idGame) || empty($move) || empty($playerName)) {
            http_response_code(400); // Code d'erreur "Bad Request"
            $response = array("error" => "Veuillez fournir un identifiant de jeu, des informations de mouvement et un nom de joueur.");
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    
        // Traitement de la demande : enregistrement du mouvement dans la base de données
        $pdo = Cpdo::GetInstance();
        $pdo->Query("INSERT INTO game_move (game_id, move, player_name) VALUES ('$idGame', '$move', '$playerName')");
        $resData = $pdo->getTabDataFromSql("SELECT * FROM game_move WHERE game_id = '$idGame' AND move = '$move' AND player_name = '$playerName'");
    
        // Envoi de la réponse
        if ($resData) {
            http_response_code(200); // Code de succès "OK"
            $response = array("status" => "success", "data" => $resData);
        } else {
            http_response_code(400); // Code d'erreur "Bad Request"
            $response = array("status" => "error", "message" => "Erreur lors de l'enregistrement du mouvement.");
        }
      }

      // Exemple : création d'une nouvelle partie avec le nom du joueur
      $gameId = $this->createNewGame($playerName);
  
      // Envoi de la réponse
      $response = array("gameId" => $gameId);
      header('Content-Type: application/json');
      echo json_encode($response);
    }

    // Resultat renvoyé à l'utilisateur lors d'une query SELECT
    public function ResultSelectQuery($success, $message, $data) {
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

    public function($idGame) {
        // Obtenir les données générées durant la partie
        $gameData = getGameData($idGame); // fonction à implémenter
    
        // Vérifier si les données ont été trouvées
        if ($gameData !== null) {
            // Définir le code de statut HTTP à 200
            http_response_code(200);
    
            // Définir l'en-tête Content-Type à application/json
            header('Content-Type: application/json');
    
            // Retourner les données au format JSON
            echo json_encode($gameData);
        } else {
            // Si les données n'ont pas été trouvées, définir le code de statut HTTP à 404
            http_response_code(404);
    
            // Retourner un message d'erreur au format JSON
            echo json_encode(array('message' => 'La partie demandée n\'a pas été trouvée.'));
        }
    }
    }
  

