<?php

namespace homepage\controller;

/**
 * Separate controller for movies - in order to minify controllers
 */
class HomeMovies extends \homepage\HomeController {

    /**
     * Basic statistical representation of movies/tv shows I&#39;ve seen
     * @Request("movies")
     * @Template("homepage/movies")
     */
    public function movies() {
        $helper = new \homepage\helpers\MoviesData($this->dic->em);
        $counters = $helper->checkForUpdate();
        $data = $this->cacheHandler->get(
            \MemcacheNamespaces::NAMESPACE_PAGE,
            \MemcacheNamespaces::KEY_MOVIES_DATA
        );
        $script = $this->cacheHandler->get(
            \MemcacheNamespaces::NAMESPACE_PAGE,
            \MemcacheNamespaces::KEY_MOVIES_JAVASCRIPT
        );

        if (
                (($data !== false) &&
                ($counters["movies"] != array_sum($data["by_decades"])) ||
                ($counters["series"] != $data["sum_series"])) ||
                ($data === false) || ($script === false)
            ) {
                $this->cacheHandler->expire(
                    \MemcacheNamespaces::NAMESPACE_PAGE,
                    \MemcacheNamespaces::KEY_MOVIES_DATA
                );
                $this->cacheHandler->expire(
                    \MemcacheNamespaces::NAMESPACE_PAGE,
                    \MemcacheNamespaces::KEY_MOVIES_JAVASCRIPT
                );
                $data = false;
                $script = false;
        }

        if (($data === false) || ($script === false)) {
            $data = $helper->getMoviesPageStats();
            $script = "//CACHED-" . date("Y-m-d H:i:s") . "\n" . $this->getMoviesJavascript($data, "1961");
            $this->cacheHandler->set(
                \MemcacheNamespaces::NAMESPACE_PAGE,
                \MemcacheNamespaces::KEY_MOVIES_DATA,
                $data,
                \MemcacheNamespaces::EXPIRE_MOVIES
            );
            $this->cacheHandler->set(
                \MemcacheNamespaces::NAMESPACE_PAGE,
                \MemcacheNamespaces::KEY_MOVIES_JAVASCRIPT,
                $script,
                \MemcacheNamespaces::EXPIRE_MOVIES
            );
        }

        $this->view->sums = array("movies" => array_sum($data["by_decades"]),
                                "series" => $data["sum_series"],
                                "genres" => array_sum($data["by_genres"]),
                                "genres_series" => array_sum($data["by_genres_series"]),
                                "countries" => array_sum($data["by_countries"]),
                                "countries_series" => array_sum($data["by_countries_series"]),
                                "directors" => $data["sum_directors"]);
        $this->view->data = array("years" => $data["by_years"],
                                "decades" => $data["by_decades"],
                                "genres" => $data["by_genres"],
                                "genres_series" => $data["by_genres_series"],
                                "countries" => $data["by_countries"],
                                "countries_series" => $data["by_countries_series"],
                                "directed" => $data["by_directed"]);
        $this->view->dataScript = $script;
    }

    /**
     * Get needed javascript variables for graphs to generate
     * @param array $data
     * @param int $begin From which year to begin first graph - count of movies
     * @return string
     */
    private function &getMoviesJavascript(array $data, $begin) {
        $return = "var d_y = [";
        $sum = 0;
        $count = 0;
        $i = 0;
        foreach ($data["by_years"] as $year => $value) {
            if ($year > $begin) {
                $return .= "[" . $i . "," . $value . "]" . (($year == date("Y")) ? "" : ",");
                $count++;
                $sum += $value;
                $i++;
            }
        }
        $return .= "];
            var d_yd = [";
        $i = 0;
        foreach ($data["by_decades"] as $decade => $value) {
            $return .= "[" . $i . "," . $value . "]" . (($decade == substr(date("Y"), 0, 3)) ? "" : ",");
            $i++;
        }
        $return .= "];";
        //mean&std
        $mean = $sum / $count;
        $st = 0;
        foreach ($data["by_years"] as $year => $value) {
            if ($year > $begin) {
                $st += pow(($value - $mean), 2);
            }
        }
        $st = sqrt($st / $count);
        //decades
        $meanD = array_sum($data["by_decades"]) / count($data["by_decades"]);
        $stD = 0;
        foreach ($data["by_decades"] as $decade => $value) {
            $stD += pow(($value - $meanD), 2);
        }
        $stD = sqrt($stD / count($data["by_decades"]));
        //corr
        $m_s = 0;
        $m_sq = 0;
        $s_sq = 0;
        foreach ($data["by_genres"] as $genre => $value) {
            $m_s += $value * $data["by_genres_series"][$genre];
            $m_sq += pow($value, 2);
            $s_sq += pow($data["by_genres_series"][$genre], 2);
        }
        $corr = ($m_s - array_sum($data["by_genres"]) * array_sum($data["by_genres_series"]) / count($data["by_genres"])) / (count($data["by_genres"]) - 1) / sqrt($m_sq * $s_sq);
        //done
        $return .= "var meanval = " . round(1 / $count, 5) . ";";
        $a = 2 / $count;
        $b = ($count - 1) / pow($count, 2) - 2 * $mean / $count;
        $return .= "var stdval = \"" . round($a, 5) . " * x<span class=\\\"sub\\\">k</span> " . ($b < 0 ? "-" : "+") . " " . abs(round($b, 5)) . "\";
            var d_y_x = [";
        $i = 0;
        foreach ($data["by_years"] as $year => $value) {
            if ($year > $begin) {
                $return .= "[" . $i . "," . $year . "]" . (($year == date("Y")) ? "" : ",");
                $i++;
            }
        }
        $return .= "];
            var d_y_m = [";
        $i = 0;
        foreach ($data["by_years"] as $year => $value) {
            if ($year > $begin) {
                $return .= "[" . $i . "," . $mean . "],";
                $i++;
            }
        }
        $return .= "[" . $i . "," . $mean . "]];
            var d_y_s = [";
        $i = 0;
        foreach ($data["by_years"] as $year => $value) {
            if ($year > $begin) {
                $return .= "[" . $i . "," . $st . "],";
                $i++;
            }
        }
        $return .= "[" . $i . "," . $st ."]];
            var d_yd_x = [";
        $i = 0;
        foreach ($data["by_decades"] as $decade => $value) {
            $return .= "[" . $i . ",'" . $decade . "0&minus;9']" . (($decade == substr(date("Y"), 0, 3)) ? "" : ",");
            $i++;
        }
        $return .= "];
            var d_yd_m = [";
        $i = 0;
        foreach ($data["by_decades"] as $decade => $value) {
            $return .= "[" . $i . "," . $meanD . "],";
            $i++;
        }
        $return .= "[" . $i . "," . $meanD . "]];
            var d_yd_s = [";
        $i = 0;
        foreach ($data["by_decades"] as $decade => $value) {
            $return .= "[" . $i . "," . $stD . "],";
            $i++;
        }
        $return .= "[" . $i ."," . $stD . "]];
            var d_g_m = [";
        $i = 0;
        foreach ($data["by_genres"] as $value) {
            $return .= "[" . $i . "," . ($value / array_sum($data["by_decades"]) * 100) . "]" . ((($i + 1) == count($data["by_genres"])) ? "" : ",");
            $i++;
        }
        $return .= "];
            var d_g_s = [";
        $i = 0;
        foreach ($data["by_genres_series"] as $value) {
            $return .= "[" . $i . "," . ($value / $data["sum_series"] * 100) . "]" . ((($i + 1) == count($data["by_genres_series"])) ? "" : ",");
            $i++;
        }
        $return .= "];
            var d_g_c = [";
        $i = 0;
        foreach ($data["by_genres"] as $value) {
            $return .= "[" . $i . "," . $corr . "]" . ((($i + 1) == count($data["by_genres"])) ? "" : ",");
            $i++;
        }
        $return .= ",[" . $i . "," . $corr . "]];
            var d_g_x = [";
        $i = 0;
        foreach ($data["genres_list"] as $genre) {
            $return .= "[" . $i . ",'" . $genre . "']" . ((($i + 1) == count($data["genres_list"])) ? "" : ",");
            $i++;
        }
        $return .= "];
            var d_d = [";
        $i = 0;
        foreach ($data["by_directed"] as $value) {
            $return .= "[" . $i . "," . ($value / $data["sum_directors"] * 100) . "]" . ((($i + 1) == count($data["by_directed"])) ? "" : ",");
            $i++;
        }
        $return .= "];
            var d_d_x = [";
        $i = 0;
        foreach ($data["by_directed"] as $movie => $value) {
            $return .= "[" . $i . "," . $movie . "]" . ((($i + 1) == count($data["by_directed"])) ? "" : ",");
            $i++;
        }
        $return .= "];";
        
        return $return;
    }
}
