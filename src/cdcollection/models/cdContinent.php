<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_continent")
 */
class cdContinent {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=13)
     */
    protected $name;
    
    /**
     * @OneToMany(targetEntity="cdCountry", mappedBy="continent")
     */
    private $countries;
    
    public function __construct() {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getCountries() {
        return $this->countries->getValues();
    }
    
    public function getName() {
        return $this->name;
    }
}
