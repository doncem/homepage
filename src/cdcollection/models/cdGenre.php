<?php
namespace cdcollection\models;
/**
 * Genre model
 * @Entity
 * @Table(name="cd_genre")
 * @package cdcollection_models
 */
class cdGenre {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of genre
     * @var string
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * Collection of artists of this genre
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdArtist", mappedBy="genre")
     */
    private $artists;
    
    /**
     * Initiate collection
     */
    public function __construct() {
        $this->artists = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * List of artists
     * @return array
     */
    public function getArtists() {
        return $this->artists->getValues();
    }
    
    /**
     * Get it
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}
