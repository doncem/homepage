<?php

namespace homepage\helpers;
use Doctrine\ORM\EntityManager;

/**
 * @IgnoreAnnotation("Annotation") 
 */
class MoviesData {
    
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function getMovies() {
        //die(var_dump($this->em->find("\homepage\models\hMovies", 1)));
        // counter by years
        $query = $this->em->createQuery("SELECT m.year,COUNT(m.id) AS counter " .
                                        "FROM \homepage\models\hMovies m " .
                                        "GROUP BY m.year " .
                                        "ORDER BY m.year");
        $years = array();
        $decades = array();
        foreach ($query->getResult() as $row) {
            $years[$row["year"]] = $row["counter"];
            // counter by decades
            if (array_key_exists(intval(substr($row["year"], 0, 3)), $decades)) {
                $decades[intval(substr($row["year"], 0, 3))] += $row["counter"];
            } else {
                $decades[intval(substr($row["year"], 0, 3))] = $row["counter"];
            }
        }
        $start = key($decades);
        $arr = array();
        foreach ($decades as $decade => $value) {
            if (($start + 1) < $decade) {
                while (($start + 1) < $decade) {
                    $arr[] = $start + 1;
                    $start++;
                }
            }
            $start = $decade;
        }
        foreach ($arr as $decade) {
            $decades[$decade] = 0;
        }
        ksort($decades);
        
        // counter by genres
        $query = $this->em->createQuery("SELECT g.genre,COUNT(DISTINCT mg.id) AS mg_counter,COUNT(DISTINCT sg.id) AS sg_counter " .
                                        "FROM \homepage\models\hGenres g " .
                                        "LEFT JOIN g.movies mg " .
                                        "LEFT JOIN g.series sg " .
                                        "GROUP BY g.genre " .
                                        "ORDER BY g.genre");
        $genre_list = array();
        $genres = array();
        $genres_series = array();
        foreach ($query->getResult() as $row) {
            $genre_list[] = $row["genre"];
            $genres[$row["genre"]] = $row["mg_counter"];
            $genres_series[$row["genre"]] = $row["sg_counter"];
        }
        
        // counter by countries
        $query = $this->em->createQuery("SELECT c.country,COUNT(mc.id) AS counter " .
                                        "FROM \homepage\models\hCountries c " .
                                        "JOIN c.movies mc " .
                                        "GROUP BY c.country " .
                                        "ORDER BY counter DESC,c.country ASC");//COUNT(mc.id)
        $countries = array();
        foreach ($query->getResult() as $row) {
            $countries[$row["country"]] = $row["counter"];
        }
        
        // counter by directors
        $query = $this->em->createQuery("SELECT d.director,COUNT(md.id) AS counter " .
                                        "FROM \homepage\models\hDirectors d " .
                                        "JOIN d.movies md " .
                                        "GROUP BY d.director " .
                                        "ORDER BY d.director");
        $directors = array();
        foreach ($query->getResult() as $row) {
            $directors[$row["director"]] = $row["counter"];
        }
        // we don't need all list of directors, just number of movies he directed
        // so let's get the array of directed movies counter
        $directed = array_count_values($directors);
        ksort($directed, SORT_NUMERIC);
        
        $query = $this->em->createQuery("SELECT COUNT(s.id) AS counter " .
                                        "FROM \homepage\models\hSeries s");
        $sum_series = 0;
        foreach ($query->getResult() as $row) {
            $sum_series = $row["counter"];
        }
        
        return array("by_years" => $years,
                     "by_decades" => $decades,
                     "by_genres" => $genres,
                     "by_genres_series" => $genres_series,
                     "by_countries" => $countries,
                     "by_directed" => $directed,
                     "genres_list" => $genre_list,
                     "sum_series" => $sum_series,
                     "sum_directors" => array_sum($directed));
    }
}
