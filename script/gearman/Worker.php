<?php
namespace script\gearman;

/**
 * Abstract class to deal with the boiler plate code of creating a gearman worker
 * @package script_gearman
 */
abstract class Worker {

    /**
     * Worker
     * @var \GearmanWorker
     */
    protected $worker;

    /**
     * Seconds between each jobs
     * @var int
     */
    protected $file_mod_check_interval = 2;

    /**
     * Max script life time
     * @var int
     */
    protected $max_runtime = 86400;

    /**
     * Initiate
     * @param \GearmanWorker $worker
     * @param string $host
     * @param int $port [optional] Default 4730
     */
    public function __construct(\GearmanWorker $worker, $host, $port = 4730) {
        $this->worker = $worker;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Run the worker
     * @throws \GearmanException
     */
    public function run() {
        $this->connect();
        $this->registerFunctions();

        $start_time = time();
        $last_file_mod_check_time = time();
        $orig_script_mod_time = filemtime(__FILE__);

        while ($this->worker->work() ||
                $this->worker->returnCode() == GEARMAN_IO_WAIT ||
                $this->worker->returnCode() == GEARMAN_NO_JOBS ||
                $this->worker->returnCode() == GEARMAN_TIMEOUT) {

            $curr_time = time();

            // Kill self if this file has been modified during execution or has been running too long
            if ($curr_time - $last_file_mod_check_time >= $this->file_mod_check_interval) {
                // clear the stat cache for this file only
                clearstatcache(true, __FILE__);
                $last_script_mod_time = filemtime(__FILE__);
                $last_file_mod_check_time = time();

                if ($orig_script_mod_time != $last_script_mod_time) {
                    echo "Worker code has changed. Stopping this process.";
                    break;
                } else if (time() - $start_time >= $this->max_runtime) {
                    echo "Script has been running for more than {$this->max_runtime}s";
                    break;
                }
            }

            if ($this->worker->returnCode() == GEARMAN_SUCCESS) {
                continue;
            }

            if (!$this->worker->wait()) {
                if ($this->worker->returnCode() == GEARMAN_NO_ACTIVE_FDS ||
                        $this->worker->returnCode() == GEARMAN_TIMEOUT) {
                    sleep(2);
                    continue;
                }

                break;
            }
        }
    }

    /**
     * Connect to the gearman server
     * @throws Ex
     */
    protected function connect() {
        if ($this->worker->addServer($this->host, $this->port) === false) {
            throw new Ex(
                "Unable to connect to Gearman at '{$this->host}:{$this->port}'",
                Ex::CONNECTION_ERROR
            );
        }
    }

    /**
     * Individual worker has to register functions to handle jobs
     */
    abstract protected function registerFunctions();
}
