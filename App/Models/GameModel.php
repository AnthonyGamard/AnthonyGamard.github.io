<?php

class GameModel
{
    private $idGame;
    private $player1_id;
    private $player2_id;
    private $player1_board;
    private $player2_board;
    private $player1_ships;
    private $player2_ships;
    private $player1_turn;

    public function __construct($idGame, $player1_id, $player2_id, $player1_board, $player2_board, $player1_ships, $player2_ships, $player1_turn)
    {
        $this->idGame = $idGame;
        $this->player1_id = $player1_id;
        $this->player2_id = $player2_id;
        $this->player1_board = $player1_board;
        $this->player2_board = $player2_board;
        $this->player1_ships = $player1_ships;
        $this->player2_ships = $player2_ships;
        $this->player1_turn = $player1_turn;
    }

    public function getIdGame()
    {
        return $this->idGame;
    }

    public function getPlayer1Id()
    {
        return $this->player1_id;
    }

    public function getPlayer2Id()
    {
        return $this->player2_id;
    }

    public function getPlayer1Board()
    {
        return $this->player1_board;
    }

    public function getPlayer2Board()
    {
        return $this->player2_board;
    }

    public function getPlayer1Ships()
    {
        return $this->player1_ships;
    }

    public function getPlayer2Ships()
    {
        return $this->player2_ships;
    }

    public function getPlayer1Turn()
    {
        return $this->player1_turn;
    }

    public function setIdGame($idGame)
    {
        $this->idGame = $idGame;
    }

    public function setPlayer1Id($player1_id)
    {
        $this->player1_id = $player1_id;
    }

    public function setPlayer2Id($player2_id)
    {
        $this->player2_id = $player2_id;
    }

    public function setPlayer1Board($player1_board)
    {
        $this->player1_board = $player1_board;
    }

    public function setPlayer2Board($player2_board)
    {
        $this->player2_board = $player2_board;
    }

    public function setPlayer1Ships($player1_ships)
    {
        $this->player1_ships = $player1_ships;
    }

    public function setPlayer2Ships($player2_ships)
    {
        $this->player2_ships = $player2_ships;
    }

    public function setPlayer1Turn($player1_turn)
    {
        $this->player1_turn = $player1_turn;
    }
}