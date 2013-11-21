<?php

namespace homepage\helpers;

/**
 * Getting all required data for stats
 * @IgnoreAnnotation
 * @package homepage_helpers
 */
class MoviesData extends \Helper {

    /**
     * Get count of current movies and series
     * @return array {
     * <br />&nbsp;&nbsp;'movies' => int,
     * <br />&nbsp;&nbsp;'series' => int
     * <br />}
     */
    public function checkForUpdate() {
        $this->em->beginTransaction();
        $counters = array();
        
        foreach (array("movies", "series") as $type) {
            $query = $this->em->createQuery("SELECT COUNT(t.id) FROM \homepage\models\h" . ucfirst($type) . " t");
            $counters[$type] = $query->getSingleScalarResult();
        }
        
        $this->em->commit();
        
        return $counters;
    }

    /**
     * Get statistics
     * @return array {
     * <br />&nbsp;&nbsp;'by_years'             => { int(year) => # },
     * <br />&nbsp;&nbsp;'by_decades'           => { int(decade) => # },
     * <br />&nbsp;&nbsp;'by_genres'            => { string(genre) => # },
     * <br />&nbsp;&nbsp;'by_genres_series'     => { string(genre) => # },
     * <br />&nbsp;&nbsp;'by_countries'         => { string(country) => # },
     * <br />&nbsp;&nbsp;'by_countries_series'  => { string(country) => # },
     * <br />&nbsp;&nbsp;'genres_list'          => { genre },
     * <br />&nbsp;&nbsp;'sum_series'           => int,
     * <br />&nbsp;&nbsp;'sum_directors'        => int
     * <br />}
     */
    public function getMoviesPageStats() {
        $this->pdo->beginTransaction();
        // counter by years
        $years = $this->gatherResults(
            $this->pdo->query(
                "SELECT m.year,COUNT(m.id) AS counter " .
                "FROM h_movies m " .
                "GROUP BY m.year " .
                "ORDER BY m.year"
            )
        );
        $decades = array();

        foreach ($years as $year => $counter) {
            if (array_key_exists(intval(substr($year, 0, 3)), $decades)) {
                $decades[intval(substr($year, 0, 3))] += $counter;
            } else {
                $decades[intval(substr($year, 0, 3))] = $counter;
            }
        }

        $start = key($decades);

        for ($i = $start; $i <= intval(substr(date("Y"), 0, 3)); $i++) {
            if (!array_key_exists($i, $decades)) {
                $decades[$i] = 0;
            }
        }

        ksort($decades);

        // counter by genres
        $genre_list = $this->gatherResults(
            $this->pdo->query(
                "SELECT g.genre,COUNT(DISTINCT mg.id) AS mg_counter,COUNT(DISTINCT sg.id) AS sg_counter " .
                "FROM h_genres g " .
                "LEFT JOIN h_movies_genres mg ON mg.genre = g.id " .
                "LEFT JOIN h_series_genres sg ON sg.genre = g.id " .
                "GROUP BY g.genre " .
                "ORDER BY g.genre"
            )
        );

        // counter by countries
        $countries = $this->gatherResults(
            $this->pdo->query(
                "SELECT c.country,COUNT(mc.id) AS counter " .
                "FROM h_countries c " .
                "INNER JOIN h_movies_countries mc ON mc.country = c.id " .
                "GROUP BY c.country " .
                "ORDER BY counter DESC,c.country ASC"
            )
        );
        $countries_series = $this->gatherResults(
            $this->pdo->query(
                "SELECT c.country,COUNT(sc.id) AS counter " .
                "FROM h_countries c " .
                "INNER JOIN h_series_countries sc ON sc.country = c.id " .
                "GROUP BY c.country " .
                "ORDER BY counter DESC,c.country ASC"
            )
        );

        // counter by directors
        $directors = $this->gatherResults(
            $this->pdo->query(
                "SELECT d.director,COUNT(md.id) AS counter " .
                "FROM h_directors d " .
                "INNER JOIN h_movies_directors md ON md.director = d.id " .
                "GROUP BY d.director " .
                "ORDER BY d.director"
            )
        );

        // we don't need all list of directors, just number of movies he directed
        $directed = array_count_values($directors);
        ksort($directed, SORT_NUMERIC);

        $sum_series = $this->gatherResults(
            $this->pdo->query("SELECT COUNT(s.id) AS counter FROM h_series s")
        );

        $this->pdo->commit();

        return array(
            "by_years" => $years,
            "by_decades" => $decades,
            "by_genres" => array_map(function($item) { return $item["mg_counter"]; }, $genre_list),
            "by_genres_series" => array_map(function($item) { return $item["sg_counter"]; }, $genre_list),
            "by_countries" => $countries,
            "by_countries_series" => $countries_series,
            "by_directed" => $directed,
            "genres_list" => array_keys($genre_list),
            "sum_series" => $sum_series["counter"],
            "sum_directors" => array_sum($directed)
        );
    }
}
