<?php
namespace services\gearman;

use xframe\core\System;
use jukebox\helpers\GetSongs;

class Worker extends \script\gearman\Worker implements \script\daemon\Daemon {

    const FUNC_SET_QUEUE = "setQueued";

    /**
     * Framework core system
     * @var System
     */
    private $system;

    public function __construct(System $system, \GearmanWorker $worker, $host, $port = 4730) {
        $this->system = $system;

        parent::__construct($worker, $host, $port);
    }
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
