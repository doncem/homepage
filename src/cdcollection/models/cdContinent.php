<?php
namespace cdcollection\models;
/**
 * Continent model
 * @Entity
 * @Table(name="cd_continent")
 */
class cdContinent {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of continent
     * @var string
     * @Column(type="string")
     * @Column(length=13)
     */
    protected $name;
    
    /**
     * Collection of countries continent has
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdCountry", mappedBy="continent")
     */
    private $countries;
    
    /**
     * Initiate collection
     */
    public function __construct() {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * List of countries
     * @return array
     */
    public function getCountries() {
        return $this->countries->getValues();
    }
    
    /**
     * Get it
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}
