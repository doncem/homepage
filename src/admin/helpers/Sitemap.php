<?php

namespace admin\helpers;

/**
 * Description of Sitemap
 * @package admin_helpers
 */
class Sitemap extends Base {

    const PAGE_LIMIT = 50;
    const NEXT_COEF = 0.85;
    const COEF_EXTERNAL = 0.23;
    const COEF_INTERNAL = 0.93;

    public function getTemplateName() {
        return "sitemap";
    }

    protected function processRegular() {
        $params = $this->request->getMappedParameters();

        if (count($params) > 1) {
            switch ($params[1]) {
                case "generate":
                    $links = $this->generateSiteMap();
                    $this->saveSiteMap($links);
                    $this->redirect();
                    break;
                default:
                    return array("error" => "Functionality not found");
            }
        }

        $file = $this->dic->root . $this->dic->registry->get("HTML_PUBLIC") . DIRECTORY_SEPARATOR . "sitemap.xml";

        if (file_exists($file)) {
            $xml = (array)simplexml_load_file($file);
            $data = $xml["url"];
        } else {
            $data = null;
        }

        return array("data" => $data);
    }

    protected function processAJAX() {
        return array("data" => "success");
    }

    private function generateSiteMap() {
        $links = $this->getPageHrefs();
        $sums = array("external" => array(), "internal" => array());
        $maxs = array("external" => 0, "internal" => 0);
        $max = 0;
        // gather sums
        foreach ($links as $info) {
            $sums["external"][] = $info["external"];
            $sums["internal"][] = $info["internal"];
        }
        // reduction after median for massive heep of link collection per page
        sort($sums["external"]);
        sort($sums["internal"]);
        $maxs["external"] = max($sums["external"]);
        $maxs["internal"] = max($sums["internal"]);
        // calculate median - attempt to recognise and reduce priority for repetitive internal links and random amount of externals
        if (count($links) % 2 == 1 && count($links) > 1) {
            $middle = floor(count($links) / 2);
            $sums["external"] = ($sums["external"][$middle - 1] + $sums["external"][$middle] + $sums["external"][$middle + 1]) / 3;
            $sums["internal"] = ($sums["internal"][$middle - 1] + $sums["internal"][$middle] + $sums["internal"][$middle + 1]) / 3;
        } else if (count($links) % 2 == 0 && count($links) > 1) {
            $middle = count($links) / 2;
            $sums["external"] = ($sums["external"][$middle - 1] + $sums["external"][$middle]) / 2;
            $sums["internal"] = ($sums["internal"][$middle - 1] + $sums["internal"][$middle]) / 2;
        } else {
            $sums["external"] = $sums["external"][0];
            $sums["internal"] = $sums["internal"][0];
        }
        // calculate multiplier and new coef - find biggest coef for normalisation
        foreach ($links as $link => $info) {
            $multiplier = ($info["external"] > $sums["external"] ? $info["external"] - $sums["external"] : $info["external"]) / $maxs["external"] * self::COEF_EXTERNAL + ($info["internal"] > $sums["internal"] ? $info["internal"] - $sums["internal"] : $info["internal"]) / $maxs["internal"] * self::COEF_INTERNAL;
            $new_coef = $info["coef"] * $multiplier;
            $links[$link]["multiplier"] = $multiplier;
            $links[$link]["new_coef"] = $new_coef;

            if ($new_coef > $max) {
                $max = $new_coef;
            }
        }
        // normalise coefs
        foreach ($links as $link => $info) {
            $links[$link]["normalised"] = $info["new_coef"] / $max;
        }

        return $links;
    }

    public function getPageHrefs() {
        $server = "http://" . $this->request->server["HTTP_HOST"];
        $hrefs = array("/");
        $links = array("/" => array(
            "coef" => 1,
            "external" => 0,
            "internal" => 0,
            "origin" => array(
                "curr_coef" => 1,
                "places" => array()
            )
        ));
        $pages = 0;

        while ($pages < count($hrefs) && $pages < self::PAGE_LIMIT) {
            $contents = file_get_contents($server . $hrefs[$pages] . "?DEPLOY");

            $found_matches = array();
            $found = preg_match_all('/\<a([^h]*|) href="(.*?)"/', $contents, $found_matches);

            if ($found > 0) {
                foreach ($found_matches[2] as $match) {
                    if ((substr($match, 0, 4) == "http") || (substr($match, 0, 2) == "//")) {
                        $links[$hrefs[$pages]]["external"]++;
                    } else if ((substr($match, 0, 1) == "#") || (substr($match, 0, 7) == "mailto:") ||
                             ($match == "javascript:void(0);")) {
                        $links[$hrefs[$pages]]["internal"]++;
                    } else if ($match != $hrefs[$pages]) {
                        $links[$hrefs[$pages]]["internal"]++;

                        if (array_key_exists($match, $links)) {
                            $origin = &$links[$match]["origin"];

                            if (count($origin["places"]) > 0) {
                                $origin["curr_coef"] *= self::NEXT_COEF;
                            }

                            $origin["places"][] = $hrefs[$pages];

                            $links[$match]["coef"] *= $origin["curr_coef"];
                        } else {
                            $hrefs[] = $match;
                            $links[$match] = array(
                                "coef" => 1,
                                "external" => 0,
                                "internal" => 0,
                                "origin" => array(
                                    "curr_coef" => 1,
                                    "places" => array()
                                )
                            );
                        }
                    }
                }
            }

            $pages++;
        }

        return $links;
    }

    private function saveSiteMap(array $links) {
        $host = filter_input(INPUT_SERVER, "HTTP_HOST");
        $sitemap = $this->dic->root . $this->dic->registry->get("HTML_PUBLIC") . DIRECTORY_SEPARATOR . "sitemap.xml";
        touch($sitemap);

        $writer = new \XMLWriter();
        $writer->openUri($sitemap);
        $writer->setIndent(true);
        $writer->setIndentString("    ");
        $writer->startDocument("1.0", "UTF-8");
        $writer->startElement("urlset");
        $writer->writeAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
        $writer->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $writer->writeAttribute("xsi:schemaLocation", "http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd");
        $writer->writeComment("created using tool originated in www.donatasmart.lt");
        $writer->writeComment("generated at " . date("Y-m-d H:i:s"));

        foreach ($links as $link => $info) {
            $writer->startElement("url");
            $writer->writeElement("loc", "http://{$host}{$link}");
            $writer->writeElement("changefreq", "monthly");
            $writer->writeElement("priority", $info["normalised"]);
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
    }
}
