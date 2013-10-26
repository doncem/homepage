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
     * @var \DateTime
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
     * Get it
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get it
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Set it
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * Get it
     * @return string
     */
    public function getTrace() {
        return $this->trace;
    }

    /**
     * Set it
     * @param string $trace
     */
    public function setTrace($trace) {
        $this->trace = $trace;
    }

    /**
     * Get it
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set it
     * @param string $ip
     * @return errLog
     */
    public function setIp($ip) {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get it
     * @return \DateTime
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * Set it
     * @param \DateTime $time
     * @return errLog
     */
    public function setTime(\DateTime $time) {
        $this->time = $time;

        return $this;
    }

    /**
     * Save the error
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function save(\Doctrine\ORM\EntityManager $em) {
        $em->persist($this);
        $em->flush();
    }
}
