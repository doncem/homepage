<?php
namespace cdcollection\models;
/**
 * Artist model
 * @Entity
 * @Table(name="cd_artist")
 */
class cdArtist {
    
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
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * Year formed
     * @var int
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $formed_in;
    
    /**
     * Info about artist
     * @var string|null
     * @Column(type="text")
     * @Column(nullable=true)
     */
    protected $info;
    
    /**
     * Info source
     * @var string|null
     * @Column(type="string")
     * @Column(length=255)
     * @Column(nullable=true)
     */
    protected $src;
    
    /**
     * Last time updated
     * @var string YYYY-MM-DD HH:II:SS
     * @Column(type="datetime")
     */
    protected $last_update;

    /**
     * Model of genre of this artist
     * @var cdGenre
     * @ManyToOne(targetEntity="cdGenre", inversedBy="artists")
     * @JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre;
    
    /**
     * Model of country of this artist
     * @var cdCountry
     * @ManyToOne(targetEntity="cdCountry", inversedBy="artists")
     * @JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;
    
    /**
     * Collection of albums of this artist
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdAlbum", mappedBy="artist")
     */
    private $albums;
    
    /**
     * Initiate collection
     */
    public function __construct() {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get it
     * @return cdGenre
     */
    public function getGenre() {
        return $this->genre;
    }
    
    /**
     * Get it
     * @return cdCountry
     */
    public function getCountry() {
        return $this->country;
    }
    
    /**
     * List of albums
     * @return array
     */
    public function getAlbums() {
        return $this->albums->getValues();
    }
    
    /**
     * Get it
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Get it
     * @return int
     */
    public function getFormedIn() {
        return $this->formed_in;
    }
    
    /**
     * Get it
     * @return string|null
     */
    public function getInfo() {
        return $this->info;
    }
    
    /**
     * Get it
     * @return string|null
     */
    public function getSrc() {
        return $this->src;
    }
    
    /**
     * Get it
     * @return string
     */
    public function getLastUpdate() {
        return $this->last_update;
    }
    
    /**
     * Get encoded url for this artist
     * @return string
     */
    public function getUrl() {
        return urlencode($this->name);
    }
}
