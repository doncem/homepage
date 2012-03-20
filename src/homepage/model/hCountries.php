<?php
namespace homepage\model;
/**
 * @Entity
 * @Table(name="h_countries")
 */
class hCountries {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=14)
     * @Column(unique=true)
     */
    protected $country;
}
