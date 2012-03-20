<?php
namespace homepage\model;
/**
 * @Entity
 * @Table(name="h_movies")
 */
class hMovies {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     */
    protected $title;
    
    /**
     * @Column(type="string")
     * @Column(nullable=true)
     */
    protected $title_en;
    
    /**
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $year;
    
    /**
     * @Column(type="string")
     * @Column(length=36)
     */
    protected $link;
    
    /**
     * @ManyToMany(targetEntity="hCountries")
     * @JoinTable(name="h_movies_countries",
     *            joinColumns={@JoinColumn(name="movie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="country", referencedColumnName="id")}
     * )
     */
    private $countries;
    
    /**
     * @ManyToMany(targetEntity="hDirectors")
     * @JoinTable(name="h_movies_directors",
     *            joinColumns={@JoinColumn(name="movie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="director", referencedColumnName="id")}
     * )
     */
    private $directors;
    
    /**
     * @ManyToMany(targetEntity="hGenres")
     * @JoinTable(name="h_movies_genres",
     *            joinColumns={@JoinColumn(name="movie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="genre", referencedColumnName="id")}
     * )
     */
    private $genres;
    
    public function __construct() {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->directors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getCountries() {
        return $this->countries->getValues();
    }
    
    public function getDirectors() {
        return $this->directors->getValues();
    }
    
    public function getGenres() {
        return $this->genres->getValues();
    }
}
