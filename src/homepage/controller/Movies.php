<?php

namespace homepage\controller;

/**
 * Separate controller for movies - in order to minify controllers
 * @package homepage_controllers
 */
class Movies extends \ControllerInit {

    /**
     * Basic statistical representation of movies/tv shows I&#39;ve seen
     * @Request("movies")
     * @Template("homepage/movies")
     */
    public function movies() {
        $helper = new \homepage\helpers\MoviesData($this->dic->database);
        $data = $helper->getMoviesPageStats();

        $this->view->sums = array(
            "movies" => array_sum($data["by_decades"]),
            "series" => $data["sum_series"],
            "genres" => array_sum($data["by_genres"]),
            "genres_series" => array_sum($data["by_genres_series"]),
            "countries" => array_sum($data["by_countries"]),
            "countries_series" => array_sum($data["by_countries_series"]),
            "directors" => $data["sum_directors"]
        );
        $sum_genres = array_sum($data["by_genres"]);
        $sum_genres_series = array_sum($data["by_genres_series"]);
        $sum_directors = $data["sum_directors"];
        $this->view->data = array(
            "years" => $data["by_years"],
            "decades" => $data["by_decades"],
            "genre_list" => $data["genres_list"],
            "genres" => array_map(function($item) use($sum_genres) {
                return round($item / $sum_genres * 100, 3);
            }, $data["by_genres"]),
            "genres_series" => array_map(function($item) use($sum_genres_series) {
                return round($item / $sum_genres_series * 100, 3);
            }, $data["by_genres_series"]),
            "countries" => $data["by_countries"],
            "countries_series" => $data["by_countries_series"],
            "directed" => array_map(function($item) use($sum_directors) {
                return round($item / $sum_directors * 100, 3);
            }, $data["by_directed"])
        );
    }
}
