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
     * @return hSeries
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
     * @return hSeries
     */
    public function setTitleEn($title = null) {
        $this->title_en = $title;

        return $this;
    }

    /**
     * Get it
     * @return int
     */
    public function getYearFrom() {
        return $this->year_from;
    }

    /**
     * Set it
     * @param int $year
     * @return hSeries
     */
    public function setYearFrom($year) {
        $this->year_from = $year;

        return $this;
    }

    /**
     * Get it
     * @return int|null
     */
    public function getYearUntil() {
        return $this->year_until;
    }

    /**
     * Set it
     * @param int $year [optional] Default null
     * @return hSeries
     */
    public function setYearUntil($year = null) {
        $this->year_until = $year;

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
     * @return hSeries
     */
    public function setLink($link) {
        $this->link = $link;

        return $this;
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
