<?php
namespace cdcollection\models;
/**
 * Label model
 * @Entity
 * @Table(name="cd_label")
 */
class cdLabel {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of label
     * @var string
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * Year founded
     * @var int|null
     * @Column(type="smallint")
     * @Column(length=4)
     * @Column(nullable=true)
     */
    protected $year_founded;
    
    /**
     * Info about label
     * @var string|null
     * @Column(type="text")
     * @Column(nullable=true)
     */
    protected $info;
    
    /**
     * Info source
     * @var string|null
     * @Column(type="string")
     * @Column(length=255)
     * @Column(nullable=true)
     */
    protected $src;
    
    /**
     * Model of country of this label
     * @var cdCountry
     * @ManyToOne(targetEntity="cdCountry", inversedBy="labels")
     * @JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;
    
    /**
     * Collection of albums of this label
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdAlbum", mappedBy="label")
     */
    private $albums;
    
    /**
     * Initiate collection
     */
    public function __construct() {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get it
     * @return cdCountry
     */
    public function getCountry() {
        return $this->country;
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
    
    /**
     * Get it
     * @return int|null
     */
    public function getYearFounded() {
        return $this->year_founded;
    }
    
    /**
     * Get it
     * @return string|null
     */
    public function getInfo() {
        return $this->info;
    }
    
    /**
     * Get it
     * @return string|null
     */
    public function getSrc() {
        return $this->src;
    }
}
