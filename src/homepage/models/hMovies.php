<?php
namespace homepage\models;
/**
 * Movie model
 * @Entity
 * @Table(name="h_movies")
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
