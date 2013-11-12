<?php
use Doctrine\ORM\EntityManager;

/**
 * Generic helper
 * @package common
 */
abstract class Helper {

    /**
     * Entity manager
     * @var EntityManager
     */
    protected $em;

    /**
     * Instantiate helper by assigning EntityManager
     * @param EntityManager $em
     * @see EntityManager
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
}
