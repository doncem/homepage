<?php
namespace cdcollection\models;
/**
 * Country model
 * @Entity
 * @Table(name="cd_country")
 */
class cdCountry {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of country
     * @var string
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * Model of continent of this country
     * @var cdContinent
     * @ManyToOne(targetEntity="cdContinent", inversedBy="countries")
     * @JoinColumn(name="continent_id", referencedColumnName="id")
     */
    private $continent;
    
    /**
     * Collection of artists of this country
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdArtist", mappedBy="country")
     */
    private $artists;
    
    /**
     * Collection of labels of this country
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdLabel", mappedBy="country")
     */
    private $labels;
    
    /**
     * Initiate collections
     */
    public function __construct() {
        $this->artists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->labels = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get it
     * @return cdContinent
     */
    public function getContinent() {
        return $this->continent;
    }

    /**
     * List of artists
     * @return array
     */
    public function getArtists() {
        return $this->artists->getValues();
    }
    
    /**
     * List of labels
     * @return array
     */
    public function getLabels() {
        return $this->labels->getValues();
    }
    
    /**
     * Get it
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}
