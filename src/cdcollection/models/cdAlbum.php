<?php
namespace cdcollection\models;
/**
 * @Entity
 * @Table(name="cd_album")
 */
class cdAlbum {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $year;
    
    /**
     * @Column(type="smallint")
     * @Column(length=2)
     * @Column(nullable=true)
     */
    protected $month;
    
    /**
     * @Column(type="smallint")
     * @Column(length=4)
     * @Column(nullable=true)
     */
    protected $r_year;
    
    /**
     * @ManyToOne(targetEntity="cdArtist", inversedBy="albums")
     * @JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;
    
    /**
     * @ManyToOne(targetEntity="cdAlbumType", inversedBy="albums")
     * @JoinColumn(name="album_type_id", referencedColumnName="id")
     */
    private $type;
    
    /**
     * @ManyToOne(targetEntity="cdLabel", inversedBy="albums")
     * @JoinColumn(name="label_id", referencedColumnName="id")
     */
    private $label;
    
    /**
     * @ManyToOne(targetEntity="cdIsOriginal", inversedBy="albums")
     * @JoinColumn(name="is_original_id", referencedColumnName="id")
     */
    private $is_original;
    
    /**
     * @OneToMany(targetEntity="cdTrack", mappedBy="album")
     */
    private $tracks;
    
    public function __construct() {
        $this->tracks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getArtist() {
        return $this->artist;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getLabel() {
        return $this->label;
    }
    
    public function getIsOriginal() {
        return $this->is_original;
    }
    
    public function getTracks() {
        return $this->tracks->getValues();
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getYear() {
        return $this->year;
    }
    
    public function getMonth() {
        return $this->month;
    }
    
    public function getRYear() {
        return $this->r_year;
    }
    
    public function getUrl() {
        return urlencode($this->name);
    }
}
