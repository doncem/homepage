<?php
namespace homepage\models;

/**
 * Director model
 * @Entity
 * @Table(name="h_directors")
 */
class hDirectors extends \SerializeMyVars {

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * Name of director
     * @var string
     * @Column(type="string")
     * @Column(unique=true)
     */
    protected $director;

    /**
     * Collection of movies having this director
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hMovies")
     * @JoinTable(name="h_movies_directors",
     *            joinColumns={@JoinColumn(name="director", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="movie", referencedColumnName="id")}
     * )
     * @OrderBy({"title" = "ASC", "year" = "ASC"})
     */
    private $movies;

    /**
     * Initiate collection
     */
    public function __construct() {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getDirector() {
        return $this->director;
    }

    /**
     * Set it
     * @param string $director
     * @return hDirectors
     */
    public function setDirector($director) {
        $this->director = $director;

        return $this;
    }

    /**
     * Get list of movies
     * @return array
     */
    public function getMovies() {
        return $this->movies->getValues();
    }
}
