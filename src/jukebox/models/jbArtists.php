<?php
namespace jukebox\models;
/**
 * Artist model
 * @Entity
 * @Table(name="jb_artists")
 */
class jbArtists extends \SerializeMyVars {

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of artist
     * @var string
     * @Column(type="string")
     * @Column(length=50)
     * @Column(unique=true)
     */
    protected $name;

    /**
     * Collection of songs by this artist
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="jbSongs", mappedBy="artist")
     */
    private $songs;

    /**
     * Initiate collection
     */
    public function __construct() {
        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get it
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get list of songs
     * @return array
     */
    public function getSongs() {
        return $this->songs->getValues();
    }
}
