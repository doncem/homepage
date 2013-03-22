<?php

namespace homepage\controller;

/**
 * Some ajax calls
 */
class HomeAjax extends \ControllerInit {

    /**
     * Instance of MemcacheSingleton object
     * @var \MemcacheSingleton
     */
    public $memcache_instance;
    /**
     * Entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    public function init() {
        parent::init();
        
        $this->memcache_instance = \MemcacheSingleton::instance($this->dic);
        $this->em = $this->dic->em;
    }
    /**
     * @Request("ajax-movies-by-year")
     * @Parameter(name="year", validator="\xframe\validation\Digit(1962,date('Y'))", required=true)
     * @View("xframe\view\JSONView")
     */
    public function moviesByYear() {
        $query = $this->em->getRepository("\homepage\models\hMovies")->findBy(
            array(
                "year" => $this->request->year
            ),
            array("title" => "ASC")
        );
        //die(var_export($query));
        $this->view->addParameter("request", $query);
    }
}
