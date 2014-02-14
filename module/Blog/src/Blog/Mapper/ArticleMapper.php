<?php

namespace Blog\Mapper;

use Blog\Entity\Article;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;

class ArticleMapper {
    
    protected $gateway;
    
    public function __construct(TableGateway $gateway) {
        $this->gateway = $gateway;
    }

    
    public function getAll() {
        $listeArticleAssoc = $this->gateway->select()->toArray();
        
        foreach ($listeArticleAssoc as $articleAssoc) {
            
            $article = new Article();
            
            $hydrator = new ClassMethods();
            $hydrator->hydrate($articleAssoc, $article);
                        
            $listeArticle[] = $article;
            
        }
        
        return $listeArticle;
    }
    
    public function getById($id) {
        $resultSet = $this->gateway->select(array("id" => $id));
        
        $articleAssoc = $resultSet->current();
        
        if($articleAssoc == null) {
            return null;
        }
        
        $article = new Article();
        $hydrator = new ClassMethods();
        $hydrator->hydrate((array) $articleAssoc, $article);
        
        return $article;
    }
    
    public function getByIdWithAuthor($id) {
        
        $adapter = $this->gateway->getAdapter();
        /* @var $adapter \Zend\Db\Adapter\Adapter  */
                
        $sql = new \Zend\Db\Sql\Sql($adapter);
        $select = $sql->select()->columns(array('*'))
                                ->from('article')
                                ->join('user', 'user_id = membre_id')
                                ->where(array("id" => $id));

        $selectString = $sql->getSqlStringForSqlObject($select);
        
        $resultSet = $adapter->query($selectString, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        
        $articleAssoc = (array) $resultSet->current();
        
        $article = new Article();
        $auteur = new \ZfcUser\Entity\User();
        
        $hydrator = new ClassMethods();
        $hydrator->hydrate($articleAssoc, $article);
        $hydrator->hydrate($articleAssoc, $auteur);
        
        $article->setAuteur($auteur);
        
        return $article;
    }
    
    public function add(Article $article) {
        
        $hydrator = new ClassMethods();
        $articleAssoc = $hydrator->extract($article);
        unset($articleAssoc["date_pub"]);
        
        $id = $this->gateway->insert($articleAssoc);
        
        $article->setId($id);
    }
    
}
