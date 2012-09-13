<?php
namespace errors\models;
/**
 * @Entity
 * @Table(name="err_log")
 */
class errLog {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;
    
    /**
     * @Column(type="text")
     */
    protected $message;
    
    /**
     * @Column(type="text")
     */
    protected $trace;
    
    /**
     * @Column(type="string")
     * @Column(length=15)
     */
    protected $ip;
    
    /**
     * @Column(type="datetime")
     * @Column(columnDefinition="timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP")
     */
    protected $time;
    
    public function __construct() {
        $this->ip = new \GetIP();
    }
    
    public function save(\Doctrine\ORM\EntityManager $em) {
        $em->persist($this);
        $em->flush();
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function setTrace($trace) {
        $this->trace = $trace;
    }
}
