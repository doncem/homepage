<?php
namespace cdcollection\models;
/**
 * Is original model
 * @Entity
 * @Table(name="cd_is_original")
 */
class cdIsOriginal {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Definition
     * @var string
     * @Column(type="string")
     * @Column(length=10)
     */
    protected $name;
    
    /**
     * Collection of albums of this originality
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdAlbum", mappedBy="is_original")
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
