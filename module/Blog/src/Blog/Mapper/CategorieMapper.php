<?php

namespace Blog\Mapper;

class CategorieMapper extends \ZfcBase\Mapper\AbstractDbMapper {
    
    public function ajouter(\Blog\Entity\Categorie $categorie) {
        
        return $this->insert($categorie, 'categorie');
        
    }
    
}
