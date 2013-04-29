<?php
namespace errors\models;
/**
 * Log model
 * @Entity
 * @Table(name="err_log")
 */
class errLog {
    
    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * Error message
     * @var string
     * @Column(type="text")
     */
    protected $message;
    
    /**
     * Error trace
     * @var string
     * @Column(type="text")
     */
    protected $trace;
    
    /**
     * Which IP address received this error?
     * @var string
     * @Column(type="string")
     * @Column(length=15)
     */
    protected $ip;
    
    /**
     * What time?
     * @var string YYYY-MM-DD HH:II:SS
     * @Column(type="datetime")
     * @Column(columnDefinition="timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP")
     */
    protected $time;
    
    /**
     * Get the IP address
     */
    public function __construct() {
        $this->ip = \GetIP::ip();
    }
    
    /**
     * Save the error
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function save(\Doctrine\ORM\EntityManager $em) {
        $em->persist($this);
        $em->flush();
    }
    
    /**
     * Set it
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }
    
    /**
     * Set it
     * @param string $trace
     */
    public function setTrace($trace) {
        $this->trace = $trace;
    }
}
