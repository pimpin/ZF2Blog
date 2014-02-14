<?php

namespace Blog\InputFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class ArticleInputFilter extends InputFilter {
    public function __construct() {
        /* Titre */
        $input = new Input("titre");
        
        $validator = new NotEmpty();
        $validator->setMessage("Le titre est obligatoire", NotEmpty::IS_EMPTY);
        $input->getValidatorChain()->attach($validator);
        
        $validator = new StringLength();
        $validator->setMin(5)
                  ->setMax(100)
                  ->setMessage("Il faut au moins %min% caractères", StringLength::TOO_SHORT)
                  ->setMessage("Il faut au maximum %max% caractères", StringLength::TOO_LONG);
        $input->getValidatorChain()->attach($validator);
        
        $filter = new StringTrim();
        $input->getFilterChain()->attach($filter);
        
        $filter = new StripTags();
        $input->getFilterChain()->attach($filter);
        
        $this->add($input);
        
        /* Contenu */
        $input = new Input("contenu");
        
        $validator = new NotEmpty();
        $validator->setMessage("Le contenu est obligatoire", NotEmpty::IS_EMPTY);
        $input->getValidatorChain()->attach($validator);
        
        $validator = new StringLength();
        $validator->setMin(25)
                  ->setMessage("Il faut au moins %min% caractères", StringLength::TOO_SHORT);
        
        $input->getValidatorChain()->attach($validator);
        
        $filter = new StringTrim();
        $input->getFilterChain()->attach($filter);
        
        $filter = new StripTags();
        $input->getFilterChain()->attach($filter);
        
        $this->add($input);
        
        /* Photo */
        $input = new Input("photo");
        $input->setRequired(false);
        
        $validator = new \Zend\Validator\File\UploadFile();
        $input->getValidatorChain()->attach($validator);
        
        $validator = new \Zend\Validator\File\MimeType();
        $validator->addMimeType("image/jpeg")
                  ->addMimeType("image/png")
                  ->addMimeType("image/gif");
                
        $input->getValidatorChain()->attach($validator);
        
        $filter = new \Zend\Filter\File\RenameUpload("public/img/");
        $filter->setRandomize(true)
               ->setUseUploadName()
               ->setUseUploadExtension();
        
        $input->getFilterChain()->attach($filter);
        
        $this->add($input);
    }

}
