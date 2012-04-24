<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_header_titles")
 */
class cdHeaderTitles {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=100)
     * @Column(unique=true)
     */
    protected $quote;
    
    /**
     * @OneToOne(targetEntity="cdTrack")
     * @JoinColumn(name="track_id", referencedColumnName="id")
     */
    private $track;
    
    public function getTrack() {
        return $this->track;
    }
    
    public function getQuote() {
        return $this->quote;
    }
}
