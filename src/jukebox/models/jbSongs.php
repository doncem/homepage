<?php
namespace jukebox\models;
/**
 * Song model
 * @Entity
 * @Table(name="jb_songs")
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
     * Initiate default counter = 0
     */
    public function __construct() {
        $this->counter = 0;
    }

    /**
     * Get it
     * @return jbArtists
     */
    public function getArtist() {
        return $this->artist;
    }

    public function setArtistId() {
        $this->artist_id = $this->artist->getId();
    }
}