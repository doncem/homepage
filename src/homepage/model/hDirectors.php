<?php
namespace homepage\model;
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
}
