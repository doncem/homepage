<?php
namespace homepage\models;
/**
 * @Entity
 * @Table(name="h_series")
 */
class hSeries {
    
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
    protected $year_from;
    
    /**
     * @Column(type="smallint")
     * @Column(length=4)
     * @Column(nullable=true)
     */
    protected $year_until;
    
    /**
     * @Column(type="string")
     * @Column(length=36)
     */
    protected $link;
    
    /**
     * @ManyToMany(targetEntity="hGenres")
     * @JoinTable(name="h_series_genres",
     *            joinColumns={@JoinColumn(name="serie", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="genre", referencedColumnName="id")}
     * )
     */
    private $genres;
    
    public function __construct() {
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getGenres() {
        return $this->genres->getValues();
    }
}
