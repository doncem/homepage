<?php
namespace cdcollection\models;
/**
 * Album type model
 * @Entity
 * @Table(name="cd_album_type")
 * @package cdcollection_models
 */
class cdAlbumType {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of album type
     * @var string
     * @Column(type="string")
     * @Column(length=25)
     */
    protected $name;
    
    /**
     * Collection of albums of this type
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdAlbum", mappedBy="type")
     */
    private $albums;
    
    /**
     * Initiate collection
     */
    public function __construct() {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
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
}
