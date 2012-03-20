<?php
namespace homepage\model;
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
}
