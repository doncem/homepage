<?php
namespace services\gearman;

use xframe\core\System;
use jukebox\helpers\GetSongs;

/**
 * General worker. Currently not so general tbh
 * @package services_gearman
 */
class Worker extends \script\gearman\Worker implements \script\daemon\Daemon {

    const FUNC_SET_QUEUE = "setQueued";

    /**
     * Framework core system
     * @var System
     */
    private $system;

    /**
     * Assign <code>system</code> we are working with
     * @param System $system
     * @param \GearmanWorker $worker
     * @param string $host
     * @param int $port [optional] Default 4730
     */
    public function __construct(System $system, \GearmanWorker $worker, $host, $port = 4730) {
        $this->system = $system;

        parent::__construct($worker, $host, $port);
    }

    /**
     * Path to PID file for Gearman
     * @return string
     */
    public function getPidPath() {
        return "www/jukebox";
    }

    /**
     * Registers the functions this worker can handle with the gearman server
     */
    protected function registerFunctions() {
        $system = $this->system;

        $this->worker->addFunction(self::FUNC_SET_QUEUE, function(\GearmanJob $job) use($system) {
            echo "Received job: " . $job->handle() . " - doing " . __METHOD__ . "\n";

            $workload = json_decode($job->workload());
            $helper = new GetSongs($system->em);

            $helper->setHistory($workload->tracks);
            $job->sendComplete(true);
        });
    }
}
