<?php
namespace homepage\models;
/**
 * @Entity
 * @Table(name="h_countries")
 */
class hCountries {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=14)
     * @Column(unique=true)
     */
    protected $country;
    
    /**
     * @ManyToMany(targetEntity="hMovies")
     * @JoinTable(name="h_movies_countries",
     *            joinColumns={@JoinColumn(name="country", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="movie", referencedColumnName="id")}
     * )
     */
    private $movies;
    
    /**
     * @ManyToMany(targetEntity="hSeries")
     * @JoinTable(name="h_series_countries",
     *            joinColumns={@JoinColumn(name="country", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="serie", referencedColumnName="id")}
     * )
     */
    private $series;
    
    public function __construct() {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getMovies() {
        return $this->movies->getValues();
    }
    
    public function getSeries() {
        return $this->series->getValues();
    }
}
