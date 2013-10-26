<?php
namespace jukebox\models;
/**
 * Queue model
 * @Entity
 * @Table(name="jb_queue")
 */
class jbQueue extends \SerializeMyVars {

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * Model of song of this queue
     * @var jbSongs
     * @ManyToOne(targetEntity="jbSongs", inversedBy="queue")
     * @JoinColumn(name="track", referencedColumnName="id")
     */
    private $track;

    /**
     * Get it
     * @return jbSongs
     */
    public function getTrack() {
        return $this->track;
    }

    /**
     * Set it
     * @param jbSongs $track
     * @return jbQueue
     */
    public function setTrack(jbSongs $track) {
        $this->track = $track;

        return $this;
    }
}
