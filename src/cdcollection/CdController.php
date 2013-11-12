<?php

namespace cdcollection;

/**
 * Initializing controller
 * @package cdcollection
 */
class CdController extends \ControllerInit {

    /**
     * <ul><li>Setting default html values</li><li>Show google scripts?</li></ul>
     */
    public function init() {
        parent::init();

        $html = new \HtmlInit($this->dic->registry);
        $this->view->html = $html->getDefaults(null,
                                               "CD Collection",
                                               null,
                                               array(
                                                   "quote" => $this->getSubtitle(),
                                                   "base_url" => "/cdcollection/"
                                               )
        );
    }
    
    /**
     * Get's quotation
     * @return array 'quote' => string(), 'place' => array(<br />
     * &nbsp;&nbsp;&nbsp;&nbsp;'artist_name' => string(),<br />
     * &nbsp;&nbsp;&nbsp;&nbsp;'artist_link' => string(),<br />
     * &nbsp;&nbsp;&nbsp;&nbsp;'track_name' => string(),<br />
     * &nbsp;&nbsp;&nbsp;&nbsp;'album_link' => string()<br />
     * )
     */
    private function getSubtitle() {
        $query = $this->dic->em->createQuery("SELECT COUNT(ht) AS counter " .
                                             "FROM \cdcollection\models\cdHeaderTitles ht");
        $max = $query->getResult();
        
        $title = $this->dic->em->getRepository("\cdcollection\models\cdHeaderTitles")
                               ->findBy(array(), array(), 1, rand(0, $max[0]["counter"] - 1));
        
        return array(
            "text" => $title[0]->getQuote(),
            "place" => array(
                "artist_name" => $title[0]->getTrack()->getAlbum()->getArtist()->getName(),
                "artist_link" => $title[0]->getTrack()->getAlbum()->getArtist()->getUrl(),
                "track_name" => $title[0]->getTrack()->getName(),
                "album_link" => $title[0]->getTrack()->getAlbum()->getUrl()
            )
        );
    }
}
