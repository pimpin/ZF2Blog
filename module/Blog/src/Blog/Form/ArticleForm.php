<?php

namespace Blog\Form;

class ArticleForm extends \Zend\Form\Form {
    public function __construct() {
        parent::__construct("article");
        
        $element = new \Zend\Form\Element\Text("titre");
        $element->setLabel("Titre")
                ->setAttribute("placeholder", "Saisir un titre");
        
        $this->add($element);
        
        $element = new \Zend\Form\Element\File("photo");
        $element->setLabel("Photo");
        
        $this->add($element);
        
        $element = new \Zend\Form\Element\Textarea("contenu");
        $element->setLabel("Contenu de l'article")
                ->setAttribute("placeholder", "Saisissez votre contenu");
        
        $this->add($element);
        
        $element = new \Zend\Form\Element\Submit("valider");
        $element->setValue("Valider");
        
        $this->add($element);
    }

}
