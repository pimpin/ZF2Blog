<?php

namespace Blog\Entity;

class Categorie {
    
    protected $id;
    protected $nom;
    protected $parentId;
    
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
        return $this;
    }


}
