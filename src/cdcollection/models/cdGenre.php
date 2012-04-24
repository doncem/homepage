<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_genre")
 */
class cdGenre {
    
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
     * @OneToMany(targetEntity="cdArtist", mappedBy="genre")
     */
    private $artists;
    
    public function __construct() {
        $this->artists = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getArtists() {
        return $this->artists->getValues();
    }
    
    public function getName() {
        return $this->name;
    }
}
