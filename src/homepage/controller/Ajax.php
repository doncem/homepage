<?php

namespace homepage\controller;

/**
 * Ajax calls used in homepage module.<br />
 * All responses are json
 * @package homepage_controllers
 */
class Ajax extends \ControllerInit {

    /**
     * Helper for movies
     * @var \homepage\helpers\MoviesData
     */
    private $model;

    protected function init() {
        parent::init();

        $this->model = new \homepage\helpers\MoviesData($this->dic->database);

        if ($this->cacheEnabled) {
            \CacheHandler::getHandler($this->dic)->set_prefix("ajax");
        }
    }

    /**
     * Get movie model repository filtered by passed year parameter.<br />
     * Year is available only between 1902 and current
     * @Request("ajax-movies-by-year")
     * @Parameter(name="year", validator="\xframe\validation\Digit(1902,date('Y'))")
     * @View("xframe\view\JSONView")
     */
    public function moviesByYear() {
        $model = $this->model;
        $movies = $this->getAndSetCache(
            \CacheVars::NAMESPACE_PAGE,
            \CacheVars::KEY_MOVIES_DATA . "_by_year_{$this->request->year}",
            function() use($model) {
                return $model->getMoviesByYear($this->request->year);
            }
        );
        $this->view->addParameter("movies", $movies);
    }

    /**
     * Get genre model repository filtered by passed genre parameter.<br />
     * Genre parameter must be a valid word.<br />
     * If no results found response contains error message
     * @Request("ajax-movies-and-series-by-genre")
     * @Parameter(name="genre", validator="\xframe\validation\RegEx('/\D+/')")
     * @View("xframe\view\JSONView")
     */
    public function moviesAndSeriesByGenre() {
        $model = $this->model;
        $genre = $this->getAndSetCache(
            \CacheVars::NAMESPACE_PAGE,
            \CacheVars::KEY_MOVIES_DATA . "_by_genre_{$this->request->genre}",
            function() use($model) {
                return $model->getByGenre($this->request->genre);
            }
        );

        if (count($genre["movie"]) + count($genre["serie"]) > 0) {
            $this->view->addParameter("movies", $genre["movie"]);
            $this->view->addParameter("series", $genre["serie"]);
        } else {
            $this->view->addParameter("error", "Nothing found :/");
        }
    }

    /**
     * Gets results depending on number of movies directors have.<br />
     * Parameter &#39;count&#39; must be between 1 and 100
     * @Request("ajax-movies-by-director-count")
     * @Parameter(name="count", validator="\xframe\validation\Digit(1,100)")
     * @View("xframe\view\JSONView")
     */
    public function moviesByDirectorCount() {
        $model = $this->model;
        $movies = $this->getAndSetCache(
            \CacheVars::NAMESPACE_PAGE,
            \CacheVars::KEY_MOVIES_DATA . "_by_director_count_{$this->request->count}",
            function() use($model) {
                return $model->getMoviesByDirectorCount($this->request->count);
            }
        );
        $this->view->addParameter("directors", $movies);
    }

    /**
     * Get country model repository filtered by passed country parameter.<br />
     * Country parameter must be a valid word.<br />
     * If no results found response contains error message
     * @Request("ajax-movies-and-series-by-country")
     * @Parameter(name="country", validator="\xframe\validation\RegEx('/\D+/')")
     * @View("xframe\view\JSONView")
     */
    public function moviesAndSeriesByCountry() {
        $model = $this->model;
        $country = $this->getAndSetCache(
            \CacheVars::NAMESPACE_PAGE,
            \CacheVars::KEY_MOVIES_DATA . "_by_country_{$this->request->country}",
            function() use($model) {
                return $model->getByCountry($this->request->country);
            }
        );

        if (count($country["movie"]) + count($country["serie"]) > 0) {
            $this->view->addParameter("movies", $country["movie"]);
            $this->view->addParameter("series", $country["serie"]);
        } else {
            $this->view->addParameter("error", "Nothing found :/");
        }
    }
}
