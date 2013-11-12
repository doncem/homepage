<?php
namespace homepage\models;

/**
 * Country model
 * @Entity
 * @Table(name="h_countries")
 * @package homepage_models
 */
class hCountries extends \SerializeMyVars {

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
     * @Column(length=14)
     * @Column(unique=true)
     */
    protected $country;

    /**
     * Collection of movies having this country
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hMovies")
     * @JoinTable(name="h_movies_countries",
     *            joinColumns={@JoinColumn(name="country", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="movie", referencedColumnName="id")}
     * )
     * @OrderBy({"title" = "ASC", "year" = "ASC"})
     */
    private $movies;

    /**
     * Collection of tv shows having this country
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hSeries")
     * @JoinTable(name="h_series_countries",
     *            joinColumns={@JoinColumn(name="country", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="serie", referencedColumnName="id")}
     * )
     * @OrderBy({"title" = "ASC", "year_from" = "ASC"})
     */
    private $series;

    /**
     * Initiate collections
     */
    public function __construct() {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get it
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get it
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set it
     * @param string $country
     * @return hCountries
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get list of movies
     * @return array
     */
    public function getMovies() {
        return $this->movies->getValues();
    }

    /**
     * Get list of tv shows
     * @return array
     */
    public function getSeries() {
        return $this->series->getValues();
    }
}
