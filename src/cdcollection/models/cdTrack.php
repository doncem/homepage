<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_track")
 */
class cdTrack {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="smallint")
     * @Column(length=1)
     */
    protected $cd_nr;
    
    /**
     * @Column(type="smallint")
     * @Column(length=2)
     */
    protected $track_nr;
    
    /**
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * @Column(type="time")
     */
    protected $length;
    
    /**
     * @Column(type="smallint")
     * @Column(length=3)
     * @Column(nullable=true)
     */
    protected $kbps;
    
    /**
     * @Column(type="text")
     * @Column(nullable=true)
     */
    protected $text;
    
    /**
     * @Column(type="integer")
     * @Column(length=11)
     */
    protected $view_count;
    
    /**
     * @ManyToOne(targetEntity="cdAlbum", inversedBy="tracks")
     * @JoinColumn(name="album_id", referencedColumnName="id")
     */
    private $album;
    
    public function __construct() {
        $this->cd_nr = 1;
        $this->view_count = 0;
    }
    
    public function getAlbum() {
        return $this->album;
    }
    
    public function getCdNr() {
        return $this->cd_nr;
    }
    
    public function getTrackNr() {
        return $this->track_nr;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function getKbps() {
        return $this->kbps;
    }
    
    public function getText() {
        return $this->text;
    }
    
    public function getViewCount() {
        return $this->view_count;
    }
}
