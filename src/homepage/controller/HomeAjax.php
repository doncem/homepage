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
     * @Parameter(name="year", validator="\xframe\validation\Digit(1962,date('Y'))")
     * @View("xframe\view\JSONView")
     */
    public function moviesByYear() {
        $movies = $this->em->getRepository("\homepage\models\hMovies")->findBy(
            array(
                "year" => $this->request->year
            ),
            array("title" => "ASC")
        );
        $this->assignGeneralMoviesData($movies);
    }
    
    /**
     * @Request("ajax-movies-and-series-by-genre")
     * @Parameter(name="genre", validator="\xframe\validation\RegEx('/\D+/')")
     * @View("xframe\view\JSONView")
     */
    public function moviesAndSeriesByGenre() {
        $genre = current($this->em->getRepository("\homepage\models\hGenres")->findBy(
            array(
                "genre" => $this->request->genre
            )
        ));
        
        if (is_object($genre)) {
            $this->assignGeneralMoviesData($genre->getMovies());
            $this->assignGeneralSeriesData($genre->getSeries());
        } else {
            $this->view->addParameter("error", "No such genre on the list. What are you doing?");
        }
    }
    
    /**
     * @Request("ajax-movies-by-director-count")
     * @Parameter(name="count", validator="\xframe\validation\Digit(1,100)")
     * @View("xframe\view\JSONView")
     */
    public function moviesByDirectorCount() {
        $directors = $this->em->createQuery(
            "SELECT d FROM \homepage\models\hDirectors d " .
            "JOIN d.movies m " .
            "GROUP BY d.id " .
            "HAVING COUNT(m.id) = :counter " .
            "ORDER BY d.director"
        )->setParameter(":counter", $this->request->count)
        ->getResult();
        
        foreach ($directors as $key => $director) {
            $this->assignGeneralMoviesData($director->getMovies(), "_" . $key);
        }
        
        $this->view->addParameter("directors", $directors);
    }
    
    /**
     * @Request("ajax-movies-and-series-by-country")
     * @Parameter(name="country", validator="\xframe\validation\RegEx('/\D+/')")
     * @View("xframe\view\JSONView")
     */
    public function moviesAndSeriesByCountry() {
        $country = current($this->em->getRepository("\homepage\models\hCountries")->findBy(
            array(
                "country" => $this->request->country
            )
        ));
        
        if (is_object($country)) {
            $this->assignGeneralMoviesData($country->getMovies());
            $this->assignGeneralSeriesData($country->getSeries());
        } else {
            $this->view->addParameter("error", "No such country on the list. What are you doing?");
        }
    }
    
    private function assignGeneralMoviesData($movies, $postFix = "") {
        $countries = $directors = $genres = array();
        
        foreach ($movies as $key => $movie) {
            $countries[$key] = $movie->getCountries();
            $directors[$key] = $movie->getDirectors();
            $genres[$key]    = $movie->getGenres();
        }

        $this->view->addParameter("movies" . $postFix,    $movies);
        $this->view->addParameter("countries" . $postFix, $countries);
        $this->view->addParameter("directors" . $postFix, $directors);
        $this->view->addParameter("genres" . $postFix,    $genres);
    }
    
    private function assignGeneralSeriesData($series) {
        $countries = $genres = array();
        
        foreach ($series as $key => $show) {
            $countries[$key] = $show->getCountries();
            $genres[$key]    = $show->getGenres();
        }
        
        $this->view->addParameter("series",           $series);
        $this->view->addParameter("series_countries", $countries);
        $this->view->addParameter("series_genres",    $genres);
    }
}
