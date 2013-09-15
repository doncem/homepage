<?php
namespace jukebox\gearman;

use jukebox\helpers\GetSongs;

class Worker extends \script\gearman\Worker implements \script\daemon\Daemon {

    const FUNC_SET_QUEUE = "setQueued";

    public function getPidPath() {
        return "www/jukebox";
    }

    /**
     * Registers the functions this worker can handle with the gearman server
     */
    protected function registerFunctions() {
        $this->worker->addFunction(self::FUNC_SET_QUEUE, function(\GearmanJob $job) {
            echo "Received job: " . $job->handle() . " - doing " . __METHOD__ . "\n";

            $workload = json_decode($job->workload());
            $system = new \xframe\core\System(__DIR__ . "/../../../", CONFIG);
            $helper = new GetSongs($system->em);

            $helper->setHistory($workload->tracks);
            $job->sendComplete(true);
        });
    }
}
