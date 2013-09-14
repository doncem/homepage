<?php
namespace jukebox\models;
/**
 * History model
 * @Entity
 * @Table(name="jb_history")
 */
class jbHistory extends \SerializeMyVars {

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * Time it's added
     * @var string
     * @Column(type="datetime")
     */
    protected $timestamp;

    /**
     * Model of continent of this country
     * @var jbSongs
     * @ManyToOne(targetEntity="jbSongs", inversedBy="history")
     * @JoinColumn(name="track", referencedColumnName="id")
     */
    private $track;

    /**
     * Get it
     * @return jbSongs
     */
    public function getTrack() {
        return $this->track;
    }
}
