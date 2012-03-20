<?php
namespace homepage\model;
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
}
