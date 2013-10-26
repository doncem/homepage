<?php
namespace homepage\models;

/**
 * Genre model
 * @Entity
 * @Table(name="h_genres")
 */
class hGenres extends \SerializeMyVars {

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * Name of genre
     * @var string
     * @Column(type="string")
     * @Column(length=11)
     * @Column(unique=true)
     */
    protected $genre;

    /**
     * Collection of movies having this genre
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hMovies")
     * @JoinTable(name="h_movies_genres",
     *            joinColumns={@JoinColumn(name="genre", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="movie", referencedColumnName="id")}
     * )
     * @OrderBy({"title" = "ASC", "year" = "ASC"})
     */
    private $movies;

    /**
     * Collection of tv shows having this genre
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hSeries")
     * @JoinTable(name="h_series_genres",
     *            joinColumns={@JoinColumn(name="genre", referencedColumnName="id")},
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
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Set it
     * @param string $genre
     * @return hGenres
     */
    public function setGenre($genre) {
        $this->genre = $genre;

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
