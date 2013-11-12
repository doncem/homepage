<?php
namespace cdcollection\models;
/**
 * Header titles model
 * @Entity
 * @Table(name="cd_header_titles")
 * @package cdcollection_models
 */
class cdHeaderTitles {
    
    /**
     * Autoincrememnt table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Quotation
     * @var string
     * @Column(type="string")
     * @Column(length=100)
     * @Column(unique=true)
     */
    protected $quote;
    
    /**
     * Model of track related to this quotation
     * @var cdTrack
     * @OneToOne(targetEntity="cdTrack")
     * @JoinColumn(name="track_id", referencedColumnName="id")
     */
    private $track;
    
    /**
     * Get it
     * @return cdTrack
     */
    public function getTrack() {
        return $this->track;
    }
    
    /**
     * Get it
     * @return string
     */
    public function getQuote() {
        return $this->quote;
    }
}
