<?php

namespace homepage\controller;

/**
 * Ajax calls used in homepage module.<br />
 * All responses are json
 * @package homepage_controllers
 */
class Ajax extends \ControllerInit {

    /**
     * Get movie model repository filtered by passed year parameter.<br />
     * Year is available only between 1902 and current
     * @Request("ajax-movies-by-year")
     * @Parameter(name="year", validator="\xframe\validation\Digit(1902,date('Y'))")
     * @View("xframe\view\JSONView")
     */
    public function moviesByYear() {
        $movies = $this->dic->em->getRepository("\homepage\models\hMovies")->findBy(
            array(
                "year" => $this->request->year
            ),
            array("title" => "ASC")
        );
        $this->assignGeneralMoviesData($movies);
    }

    /**
     * Get genre model repository filtered by passed genre parameter.<br />
     * Genre parameter must be a valid word.<br />
     * If no such genre found response contains error message
     * @Request("ajax-movies-and-series-by-genre")
     * @Parameter(name="genre", validator="\xframe\validation\RegEx('/\D+/')")
     * @View("xframe\view\JSONView")
     */
    public function moviesAndSeriesByGenre() {
        $genre = current($this->dic->em->getRepository("\homepage\models\hGenres")->findBy(
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
     * Gets results depending on number of movies directors have.<br />
     * Parameter &#39;count&#39; must be between 1 and 100
     * @Request("ajax-movies-by-director-count")
     * @Parameter(name="count", validator="\xframe\validation\Digit(1,100)")
     * @View("xframe\view\JSONView")
     */
    public function moviesByDirectorCount() {
        $directors = $this->dic->em->createQuery(
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
     * Get country model repository filtered by passed country parameter.<br />
     * Country parameter must be a valid word.<br />
     * If no such country found response contains error message
     * @Request("ajax-movies-and-series-by-country")
     * @Parameter(name="country", validator="\xframe\validation\RegEx('/\D+/')")
     * @View("xframe\view\JSONView")
     */
    public function moviesAndSeriesByCountry() {
        $country = current($this->dic->em->getRepository("\homepage\models\hCountries")->findBy(
            array(
                "country" => urldecode($this->request->country)
            )
        ));

        if (is_object($country)) {
            $this->assignGeneralMoviesData($country->getMovies());
            $this->assignGeneralSeriesData($country->getSeries());
        } else {
            $this->view->addParameter("error", "No such country on the list. What are you doing?");
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
