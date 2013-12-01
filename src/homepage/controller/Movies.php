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
        $data = $this->getData();

        $this->view->sums = array(
            "movies"            => array_sum($data["by_decades"]),
            "series"            => $data["sum_series"],
            "genres"            => array_sum($data["by_genres"]),
            "genres_series"     => array_sum($data["by_genres_series"]),
            "countries"         => array_sum($data["by_countries"]),
            "countries_series"  => array_sum($data["by_countries_series"]),
            "directors"         => $data["sum_directors"]
        );
        $sum_movies = array_sum($data["by_decades"]);
        $sum_series = $data["sum_series"];
        $sum_directors = $data["sum_directors"];
        $this->view->data = array(
            "years"             => $data["by_years"],
            "decades"           => $data["by_decades"],
            "genre_list"        => $data["genres_list"],
            "genres"            => array_map(function($item) use($sum_movies) {
                return round($item / $sum_movies * 100, 3);
            }, $data["by_genres"]),
            "genres_series"     => array_map(function($item) use($sum_series) {
                return round($item / $sum_series * 100, 3);
            }, $data["by_genres_series"]),
            "countries"         => $data["by_countries"],
            "countries_series"  => $data["by_countries_series"],
            "directed"          => array_map(function($item) use($sum_directors) {
                return round($item / $sum_directors * 100, 3);
            }, $data["by_directed"])
        );
        $this->view->dataAccessed = $data["accessed"];
    }

    private function &getData() {
        $helper = new \homepage\helpers\MoviesData($this->dic->database);
        $update = false;

        if ($this->cacheEnabled) {
            $arr = $helper->checkForUpdate();
            $data = $this->getFromCache(\CacheVars::NAMESPACE_PAGE, \CacheVars::KEY_MOVIES_DATA);

            if (($data === false) || (is_array($data) &&
                    ($arr["movies"] != array_sum($data["by_decades"]) || $arr["series"] != $data["sum_series"]))) {
                $update = true;

                if ($data !== false) {
                    // without identifier because need to expire in ajax calls as well
                    $this->expireCache(\CacheVars::NAMESPACE_PAGE);
                }
            }
        }

        if ($update || !$this->cacheEnabled) {
            $data = $helper->getMoviesPageStats();
            $data["accessed"] = date("Y-m-d H:i:s");

            if ($this->cacheEnabled) {
                $this->setToCache(\CacheVars::NAMESPACE_PAGE, \CacheVars::KEY_MOVIES_DATA, $data);
            }
        }

        return $data;
    }
}
