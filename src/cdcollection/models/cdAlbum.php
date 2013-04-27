<?php
namespace cdcollection\models;
/**
 * Album model
 * @Entity
 * @Table(name="cd_album")
 */
class cdAlbum {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Name of album
     * @var string
     * @Column(type="string")
     * @Column(length=255)
     */
    protected $name;
    
    /**
     * Year of publish
     * @var int
     * @Column(type="smallint")
     * @Column(length=4)
     */
    protected $year;
    
    /**
     * Month of publish
     * @var int|null
     * @Column(type="smallint")
     * @Column(length=2)
     * @Column(nullable=true)
     */
    protected $month;
    
    /**
     * Re-issue year
     * @var int|null
     * @Column(type="smallint")
     * @Column(length=4)
     * @Column(nullable=true)
     */
    protected $r_year;
    
    /**
     * Model of artist of this album
     * @var cdArtist
     * @ManyToOne(targetEntity="cdArtist", inversedBy="albums")
     * @JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;
    
    /**
     * Model of album type of this album
     * @var cdAlbumType
     * @ManyToOne(targetEntity="cdAlbumType", inversedBy="albums")
     * @JoinColumn(name="album_type_id", referencedColumnName="id")
     */
    private $type;
    
    /**
     * Model of label of this album
     * @var cdLabel
     * @ManyToOne(targetEntity="cdLabel", inversedBy="albums")
     * @JoinColumn(name="label_id", referencedColumnName="id")
     */
    private $label;
    
    /**
     * Model of is original of this album
     * @var cdIsOriginal
     * @ManyToOne(targetEntity="cdIsOriginal", inversedBy="albums")
     * @JoinColumn(name="is_original_id", referencedColumnName="id")
     */
    private $is_original;
    
    /**
     * Collection of track of this album
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="cdTrack", mappedBy="album")
     */
    private $tracks;
    
    /**
     * Initiate collection
     */
    public function __construct() {
        $this->tracks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get it
     * @return cdArtist
     */
    public function getArtist() {
        return $this->artist;
    }
    
    /**
     * Get it
     * @return cdAlbumType
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * Get it
     * @return cdLabel
     */
    public function getLabel() {
        return $this->label;
    }
    
    /**
     * Get it
     * @return cdIsOriginal
     */
    public function getIsOriginal() {
        return $this->is_original;
    }
    
    /**
     * List of tracks
     * @return array
     */
    public function getTracks() {
        return $this->tracks->getValues();
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
     * @return int
     */
    public function getYear() {
        return $this->year;
    }
    
    /**
     * Get it
     * @return int|null
     */
    public function getMonth() {
        return $this->month;
    }
    
    /**
     * Get it
     * @return int|null
     */
    public function getRYear() {
        return $this->r_year;
    }
    
    /**
     * Get encoded url for this album
     * @return string
     */
    public function getUrl() {
        return urlencode($this->name);
    }
}
