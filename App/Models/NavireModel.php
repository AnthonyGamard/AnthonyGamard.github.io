<?php

namespace App\Models;


class NavireModel
{
    private $id;
    private $nom;
    private $nom_technique;
    private $longueur;

    public function __construct($id, $nom, $nom_technique, $longueur)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->nom_technique = $nom_technique;
        $this->longueur = $longueur;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getNomTechnique()
    {
        return $this->nom_technique;
    }

    public function getLongueur()
    {
        return $this->longueur;
    }
}