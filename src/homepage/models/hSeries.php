<?php
namespace homepage\models;
/**
 * Tv show model
 * @Entity
 * @Table(name="h_series")
 */
class hSeries extends \SerializeMyVars {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Original title
     * @var string
     * @Column(type="string")
     */
    protected $title;
    
    /**
     * English title
     * @var string
     * @Column(type="string")
     * @Column(nullable=true)
     */
    protected $title_en;
    
    /**
     * Year started
     * @var int
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $year_from;
    
    /**
     * Year ended
     * @var int|null
     * @Column(type="smallint")
     * @Column(length=4)
     * @Column(nullable=true)
     */
    protected $year_until;
    
    /**
     * Link to imdb
     * @var string
     * @Column(type="string")
     * @Column(length=36)
     */
    protected $link;
    
    /**
     * Collection of countries this tv show has
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hCountries")
     * @JoinTable(name="h_series_countries",
     *            joinColumns={@JoinColumn(name="serie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="country", referencedColumnName="id")}
     * )
     * @OrderBy({"country" = "ASC"})
     */
    private $countries;
    
    /**
     * Collection of genres this tv show has
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hGenres")
     * @JoinTable(name="h_series_genres",
     *            joinColumns={@JoinColumn(name="serie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="genre", referencedColumnName="id")}
     * )
     * @OrderBy({"genre" = "ASC"})
     */
    private $genres;
    
    /**
     * Initiate collections
     */
    public function __construct() {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get list of countries
     * @return array
     */
    public function getCountries() {
        return $this->countries->getValues();
    }
    
    /**
     * Get list of genres
     * @return array
     */
    public function getGenres() {
        return $this->genres->getValues();
    }
}
