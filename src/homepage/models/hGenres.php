<?php
namespace homepage\models;
/**
 * @Entity
 * @Table(name="h_genres")
 */
class hGenres {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=11)
     * @Column(unique=true)
     */
    protected $genre;
    
    /**
     * @ManyToMany(targetEntity="hMovies")
     * @JoinTable(name="h_movies_genres",
     *            joinColumns={@JoinColumn(name="genre", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="movie", referencedColumnName="id")}
     * )
     */
    private $movies;
    
    /**
     * @ManyToMany(targetEntity="hSeries")
     * @JoinTable(name="h_series_genres",
     *            joinColumns={@JoinColumn(name="genre", referencedColumnName="id")},
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
