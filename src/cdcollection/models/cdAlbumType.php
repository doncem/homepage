<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_album_type")
 */
class cdAlbumType {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=25)
     */
    protected $name;
    
    /**
     * @OneToMany(targetEntity="cdAlbum", mappedBy="type")
     */
    private $albums;
    
    public function __construct() {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getAlbums() {
        return $this->albums->getValues();
    }
    
    public function getName() {
        return $this->name;
    }
}
