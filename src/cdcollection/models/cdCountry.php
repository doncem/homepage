<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_country")
 */
class cdCountry {
    
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
     * @ManyToOne(targetEntity="cdContinent", inversedBy="countries")
     * @JoinColumn(name="continent_id", referencedColumnName="id")
     */
    private $continent;
    
    /**
     * @OneToMany(targetEntity="cdArtist", mappedBy="country")
     */
    private $artists;
    
    /**
     * @OneToMany(targetEntity="cdLabel", mappedBy="country")
     */
    private $labels;
    
    public function __construct() {
        $this->artists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->labels = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getContinent() {
        return $this->continent;
    }

    public function getArtists() {
        return $this->artists->getValues();
    }
    
    public function getLabels() {
        return $this->labels->getValues();
    }
    
    public function getName() {
        return $this->name;
    }
}
