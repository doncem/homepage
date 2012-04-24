<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_is_original")
 */
class cdIsOriginal {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=10)
     */
    protected $name;
    
    /**
     * @OneToMany(targetEntity="cdAlbum", mappedBy="is_original")
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
