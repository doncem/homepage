<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_label")
 */
class cdLabel {
    
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
     * @Column(nullable=true)
     */
    protected $year_founded;
    
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
     * @ManyToOne(targetEntity="cdCountry", inversedBy="labels")
     * @JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;
    
    /**
     * @OneToMany(targetEntity="cdAlbum", mappedBy="label")
     */
    private $albums;
    
    public function __construct() {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
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
    
    public function getYearFounded() {
        return $this->year_founded;
    }
    
    public function getInfo() {
        return $this->info;
    }
    
    public function getSrc() {
        return $this->src;
    }
}
