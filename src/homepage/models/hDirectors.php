<?php
namespace homepage\models;
/**
 * @Entity
 * @Table(name="h_directors")
 */
class hDirectors {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(unique=true)
     */
    protected $director;
    
    /**
     * @ManyToMany(targetEntity="hMovies")
     * @JoinTable(name="h_movies_directors",
     *            joinColumns={@JoinColumn(name="director", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn(name="movie", referencedColumnName="id")}
     * )
     */
    private $movies;
    
    public function __construct() {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getMovies() {
        return $this->movies->getValues();
    }
}
