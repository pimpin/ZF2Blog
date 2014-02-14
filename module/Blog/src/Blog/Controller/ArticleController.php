<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ArticleController extends AbstractActionController
{
    /**
     *
     * @var \Zend\Http\Request
     */
    protected $request;
    
    private function getMapper() {
        // Service Manager
        // Composant qui stocke les objets associées à des clés
        // et qui sait les créer (avec new, avec une fabrique, avec singleton,
        // avec une fabrique abstraite, avec un builder...)
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $gateway = new \Zend\Db\TableGateway\TableGateway("article", $adapter);
        return new \Blog\Mapper\ArticleMapper($gateway);
    }

    public function listerAction()
    {
        $mapper = $this->getMapper();
        $listeArticles = $mapper->getAll();
        
        // TODO chercher plus simple pour modifier le titre de la page en cours
        $viewHelperManager = $this->getServiceLocator()->get('viewHelperManager');
        $headTitleHelper   = $viewHelperManager->get('headTitle');
        $headTitleHelper->append("Liste des articles");
        
        return new ViewModel(array(
            "listeArticles" => $listeArticles,
        ));
    }

    public function detailsAction()
    {
        // Va chercher le paramètres id dans la route
        $id = $this->params("id");
        
        // Interroge le Model pour récupérer notre article
        $mapper = $this->getMapper();
        $article = $mapper->getByIdWithAuthor($id);
        
        // TODO si pas d'article
        // au choix rediriger ou erreur 404
        
        // On transmet l'article à la vue
        return new ViewModel(array(
            "article" => $article,
        ));
    }

    public function ajouterAction()
    {
        if(!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute("zfcuser/login");
        }
        
        
        $form = new \Blog\Form\ArticleForm();
        
        if($this->request->isPost()) {
            $form->setInputFilter(new \Blog\InputFilter\ArticleInputFilter());
            
            $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
            
            $form->setData($data);
            
            if($form->isValid()) {
                $mapper = $this->getMapper();
                
                $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
                $article = new \Blog\Entity\Article();
                
                $idUserConnected = $this->zfcUserAuthentication()->getIdentity()->getId();
                $article->setMembreId($idUserConnected);
                
                $data = $form->getData();
                $newImgName = array_shift(array_reverse(explode("/", $data["photo"]["tmp_name"])));
                
                $data["photo"] = $newImgName;
                
                $hydrator->hydrate($data, $article);
                
                $mapper->add($article);
                
                $this->flashMessenger()->addSuccessMessage("L'article a bien été publié");
                
                return $this->redirect()->toRoute("home");
            }
        }
        
        $form->prepare();
        
        return new ViewModel(array(
            "form" => $form
        ));
    }


}

