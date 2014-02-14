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


}

