<?php
namespace jukebox\models;

/**
 * Song model
 * @Entity
 * @Table(name="jb_songs")
 * @package jukebox_models
 */
class jbSongs extends \SerializeMyVars {

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * Should be set up while querying
     * @var int
     */
    protected $artist_id;

    /**
     * Name of song
     * @var string
     * @Column(type="string")
     * @Column(length=45)
     */
    protected $name;

    /**
     * Filename
     * @var string
     * @Column(type="string")
     * @Column(length=15)
     */
    protected $filename;

    /**
     * Song sping counter
     * @var int
     * @Column(type="integer")
     */
    protected $counter;

    /**
     * Model of continent of this country
     * @var jbArtists
     * @ManyToOne(targetEntity="jbArtists", inversedBy="songs")
     * @JoinColumn(name="artist", referencedColumnName="id")
     */
    private $artist;

    /**
     * Collection of history entries by this song
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="jbHistory", mappedBy="track")
     */
    private $history;

    /**
     * Collection of queue entries by this song
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="jbQueue", mappedBy="track")
     */
    private $queue;

    /**
     * Initiate default counter = 0 and collections
     */
    public function __construct() {
        $this->counter = 0;
        $this->history = new \Doctrine\Common\Collections\ArrayCollection();
        $this->queue = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get it
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get it
     * @return int
     */
    public function getArtistId() {
        return $this->artist_id;
    }

    /**
     * Set it
     * @return jbSongs
     */
    public function setArtistId() {
        $this->artist_id = $this->artist->getId();

        return $this;
    }

    /**
     * Get it
     * @return jbArtists
     */
    public function getArtist() {
        return $this->artist;
    }

    /**
     * Get it
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set it
     * @param string $name
     * @return jbSongs
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get it
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set it
     * @param string $filename
     * @return jbSongs
     */
    public function setFilename($filename) {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get it
     * @return int
     */
    public function getCounter() {
        return $this->counter;
    }

    /**
     * Set it
     * @param int $counter
     * @return jbSongs
     */
    public function setCounter($counter) {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get list of histories
     * @return array
     */
    public function getHistory() {
        return $this->history->getValues();
    }

    /**
     * Get list of queues
     * @return array
     */
    public function getQueue() {
        return $this->queue->getValues();
    }
}
