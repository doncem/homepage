<?php

/**
 * Generic helper
 * @package common
 */
abstract class Helper {

    /**
     * DB
     * @var \PDO
     */
    protected $pdo;

    /**
     * Instantiate helper by assigning DB connection
     * @param \PDO $em
     */
    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Gather results into an array of { &lt;first-column&gt; => &lt;second-column&gt; } mappings
     * @param \PDOStatement $statement
     * @return array
     * @throws Exception
     */
    protected function gatherResults(\PDOStatement $statement) {
        if ($statement->execute()) {
            $columns = $statement->columnCount();
            $fix_results = false;

            if ($columns == 1) {
                $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

                if (count($results) == 1) {
                    $results = current($results);
                }
            } else if ($columns == 2) {
                $results = $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
            } else {
                $results = $statement->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC);
                $fix_results = true;
            }

            if ($fix_results) {
                $results = array_map(function($item) {
                    return current($item);
                }, $results);
            }

            return $results;
        } else {
            throw new Exception("Could not execute PDOStatement :/", $statement->errorCode(), $statement->errorInfo());
        }
    }
}
