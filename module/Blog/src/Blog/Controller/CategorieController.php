<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategorieController extends AbstractActionController
{

    public function indexAction()
    {
        
        
        return new ViewModel();
    }

    public function ajouterAction()
    {
        if($this->request->isPost()) {
            $mapper = new \Blog\Mapper\CategorieMapper();
            $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $mapper->setDbAdapter($adapter);
            
            $data = $this->request->getPost();
            
            $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
            $categorie = new \Blog\Entity\Categorie();
            $mapper->setEntityPrototype($categorie);
            $hydrator->hydrate((array) $data, $categorie);
            
            $mapper->ajouter($categorie);
            
            
                
        }
        
        return new ViewModel();
    }


}

