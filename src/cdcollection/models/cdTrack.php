<?php
namespace cdcollection\models;
/**
 * Track model
 * @Entity
 * @Table(name="cd_track")
 */
class cdTrack {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * CD number
     * @var int
     * @Column(type="smallint")
     * @Column(length=1)
     */
    protected $cd_nr;
    
    /**
     * Track number
     * @var int
     * @Column(type="smallint")
     * @Column(length=2)
     */
    protected $track_nr;
    
    /**
     * Name of track
     * @var string
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * Track length
     * @var string HH:II:SS
     * @Column(type="time")
     */
    protected $length;
    
    /**
     * What kbps rate do I have?
     * @var int|null
     * @Column(type="smallint")
     * @Column(length=3)
     * @Column(nullable=true)
     */
    protected $kbps;
    
    /**
     * Track lyrics
     * @var string|null
     * @Column(type="text")
     * @Column(nullable=true)
     */
    protected $text;
    
    /**
     * How many times lyrics were viewed?
     * @var int
     * @Column(type="integer")
     * @Column(length=11)
     */
    protected $view_count;
    
    /**
     * Model of album of this track
     * @var cdAlbum
     * @ManyToOne(targetEntity="cdAlbum", inversedBy="tracks")
     * @JoinColumn(name="album_id", referencedColumnName="id")
     */
    private $album;
    
    /**
     * Set defaults: CD number 1 and view counter 0
     */
    public function __construct() {
        $this->cd_nr = 1;
        $this->view_count = 0;
    }
    
    /**
     * Get it
     * @return cdAlbum
     */
    public function getAlbum() {
        return $this->album;
    }
    
    /**
     * Get it
     * @return int
     */
    public function getCdNr() {
        return $this->cd_nr;
    }
    
    /**
     * Get it
     * @return int
     */
    public function getTrackNr() {
        return $this->track_nr;
    }
    
    /**
     * Get it
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Get it
     * @return string
     */
    public function getLength() {
        return $this->length;
    }
    
    /**
     * Get it
     * @return int|null
     */
    public function getKbps() {
        return $this->kbps;
    }
    
    /**
     * Get it
     * @return string|null
     */
    public function getText() {
        return $this->text;
    }
    
    /**
     * Get it
     * @return int
     */
    public function getViewCount() {
        return $this->view_count;
    }
}
