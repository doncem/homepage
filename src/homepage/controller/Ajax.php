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
    }

    /**
     * Get movie model repository filtered by passed year parameter.<br />
     * Year is available only between 1902 and current
     * @Request("ajax-movies-by-year")
     * @Parameter(name="year", validator="\xframe\validation\Digit(1902,date('Y'))")
     * @View("xframe\view\JSONView")
     */
    public function moviesByYear() {
        $movies = $this->model->getMoviesByYear($this->request->year);
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
        $genre = $this->model->getByGenre($this->request->genre);

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
        $movies = $this->model->getMoviesByDirectorCount($this->request->count);
        $this->view->addParameter("movies", $movies);
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
        $country = $this->model->getByCountry($this->request->country);

        if (count($country["movie"]) + count($country["serie"]) > 0) {
            $this->view->addParameter("movies", $country["movie"]);
            $this->view->addParameter("series", $country["serie"]);
        } else {
            $this->view->addParameter("error", "Nothing found :/");
        }
    }

    /**
     * Parses given array of &#39;movies&#39; and adds variables to the response
     * @param array $movies
     * @param string $postFix [optional] Default &#39;&#39;
     */
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

    /**
     * Parses given array of &#39;series&#39; and adds variables to the response
     * @param array $series
     */
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
