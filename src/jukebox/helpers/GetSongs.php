<?php
namespace jukebox\helpers;

use Doctrine\Common\Collections\Criteria;

/**
 * Getting all required data for stats
 * @IgnoreAnnotation
 * @package jukebox_helpers
 */
class GetSongs extends \Helper {

    /**
     * Return search results or all if search query is empty
     * @param string $query
     * @return array {
     * <br />&nbsp;&nbsp;'movies' => array { objects },
     * <br />&nbsp;&nbsp;'series' => array { objects }
     * <br />} || array { objects }
     */
    public function search($query) {
        if (strlen($query) > 0) {
            $query = htmlentities($query, ENT_QUOTES | ENT_HTML5);
            $criteria = Criteria::create();
            $matching = $criteria->where(Criteria::expr()->contains("name", $query))
                                 ->orderBy(array("name" => Criteria::ASC));

            $songs = $this->em->getRepository("\jukebox\models\jbSongs")
                              ->matching($matching);
            $songs->forAll(function($key, $el) {
                $el->setArtistId();
                return true;
            });

            return array(
                "artists" => $this->em->getRepository("\jukebox\models\jbArtists")
                                      ->matching($matching)->toArray(),
                "songs" => $songs->toArray()
            );
        } else {
            $songs = $this->em->getRepository("\jukebox\models\jbSongs")->findAll();

            foreach ($songs as $song) {
                $song->setArtistId();
            }

            return array(
                "artists" => $this->em->getRepository("\jukebox\models\jbArtists")->findAll(),
                "songs" => $songs
            );
        }
    }

    /**
     * Return history
     * @param \DateTime $from [optional] Default null
     * @return array { objects }
     */
    public function getHistory(\DateTime $from = null) {
        $matching = Criteria::create()->where(
            Criteria::expr()->gte(
                "timestamp", is_null($from) ? new \DateTime("-2 months") : $from
            )
        );

        return $this->em->getRepository("\jukebox\models\jbHistory")
                        ->matching($matching)
                        ->toArray();
    }

    /**
     * Get all queue
     * @return array
     */
    public function getQueue() {
        return $this->em->getRepository("\jukebox\models\jbQueue")->findAll();
    }

    /**
     * Save tracks into queue
     * @param array $tracks
     */
    public function setQueue(array $tracks) {
        $date = new \DateTime();

        foreach ($tracks as $track) {
            $history = new \jukebox\models\jbHistory();
            $queue = new \jukebox\models\jbQueue();
            $track = $this->em->getReference("\jukebox\models\jbSongs", $track);

            $history->setTimestamp($date)
                    ->setTrack($track);
            $queue->setTrack($track);

            $this->em->persist($history);
            $this->em->persist($queue);
        }
 
        $this->em->flush();
        $this->em->clear();
    }
}
