<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_artist")
 */
class cdArtist {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $formed_in;
    
    /**
     * @Column(type="text")
     * @Column(nullable=true)
     */
    protected $info;
    
    /**
     * @Column(type="string")
     * @Column(length=255)
     * @Column(nullable=true)
     */
    protected $src;
    
    /**
     * @Column(type="datetime")
     */
    protected $last_update;

    /**
     * @ManyToOne(targetEntity="cdGenre", inversedBy="artists")
     * @JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre;
    
    /**
     * @ManyToOne(targetEntity="cdCountry", inversedBy="artists")
     * @JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;
    
    /**
     * @OneToMany(targetEntity="cdAlbum", mappedBy="artist")
     */
    private $albums;
    
    public function __construct() {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getGenre() {
        return $this->genre;
    }
    
    public function getCountry() {
        return $this->country;
    }
    
    public function getAlbums() {
        return $this->albums->getValues();
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getFormedIn() {
        return $this->formed_in;
    }
    
    public function getInfo() {
        return $this->info;
    }
    
    public function getSrc() {
        return $this->src;
    }
    
    public function getLastUpdate() {
        return $this->last_update;
    }
    
    public function getUrl() {
        return urlencode($this->name);
    }
}
