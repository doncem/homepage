<?php
namespace homepage\models;

/**
 * Movie model
 * @Entity
 * @Table(name="h_movies")
 * @package homepage_models
 */
class hMovies extends \SerializeMyVars {

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
     * @var string|null
     * @Column(type="string")
     * @Column(nullable=true)
     */
    protected $title_en;

    /**
     * Year published
     * @var int
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $year;

    /**
     * Link to imdb
     * @var string
     * @Column(type="string")
     * @Column(length=36)
     */
    protected $link;

    /**
     * Collection of countries this movie has
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hCountries")
     * @JoinTable(name="h_movies_countries",
     *            joinColumns={@JoinColumn(name="movie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="country", referencedColumnName="id")}
     * )
     * @OrderBy({"country" = "ASC"})
     */
    private $countries;

    /**
     * Collection of directors this movie has
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hDirectors")
     * @JoinTable(name="h_movies_directors",
     *            joinColumns={@JoinColumn(name="movie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="director", referencedColumnName="id")}
     * )
     * @OrderBy({"director" = "ASC"})
     */
    private $directors;

    /**
     * Collection of genres this movie has
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="hGenres")
     * @JoinTable(name="h_movies_genres",
     *            joinColumns={@JoinColumn(name="movie", referencedColumnName="id")},
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
        $this->directors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set it
     * @param string $title
     * @return hMovies
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get it
     * @return string|null
     */
    public function getTitleEn() {
        return $this->title_en;
    }

    /**
     * Set it
     * @param string $title [optional] Default null
     * @return hMovies
     */
    public function setTitleEn($title = null) {
        $this->title_en = $title;

        return $this;
    }

    /**
     * Get it
     * @return int
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Set it
     * @param int $year
     * @return hMovies
     */
    public function setYear($year) {
        $this->year = $year;

        return $this;
    }

    /**
     * Get it
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Set it
     * @param string $link
     * @return hMovies
     */
    public function setLink($link) {
        $this->link = $link;

        return $this;
    }

    /**
     * List of countries
     * @return array
     */
    public function getCountries() {
        return $this->countries->getValues();
    }
    
    /**
     * List of directors
     * @return array
     */
    public function getDirectors() {
        return $this->directors->getValues();
    }
    
    /**
     * List of genres
     * @return array
     */
    public function getGenres() {
        return $this->genres->getValues();
    }
}
