<?php

use Doctrine\ORM\EntityManager;

/**
 * Generic helper
 * @package common
 */
abstract class DbDoctrineHelper {

    /**
     * Entity manager
     * @var EntityManager
     */
    protected $em;

    /**
     * Instantiate helper by assigning Doctrine entity manager
     * @param \PDO $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
}
