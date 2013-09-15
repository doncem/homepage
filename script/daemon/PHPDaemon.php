<?php
namespace script\daemon;

/**
 * Use this class to daemonize your php script. Used for gearman workers
 */
class PHPDaemon {

    /**
     * Current daemon
     * @var Daemon
     */
    protected $daemon;

    /**
     * Location of pid file
     * @var string
     */
    protected $pid_path;

    /**
     * Initiate
     * @param Daemon $daemon
     */
    public function __construct(Daemon $daemon) {
        $this->daemon = $daemon;
        $this->pid_path = "/var/run/" . $this->daemon->get_pid_path() . ".pid";
    }

    /**
     * Daemonizes the requested object
     * @return boolean|null
     */
    public function daemonize() {
        if ($this->alreadyRunning()) {
            return false;
        }

        $this->savePidFile();
        $this->daemon->run();
    }

    /**
     * Saves the current process id into the pid file
     */
    protected function savePidFile() {
        $dir = dirname($this->pid_path);

        if (!is_writable($dir)) {
            $this->createPidDir($dir);
        }

        $fp = fopen($this->pid_path, "w");

        if ($fp === false) {
            echo "Unable to open pid file '{$this->pid_path}'\n";
            die();
        }

        fwrite($fp, getmypid());
        fclose($fp);
    }

    /**
     * Creates directory path and makes it writable.
     * @param string $dir_path
     */
    protected function createPidDir($dir_path) {
        $dirs = explode(DIRECTORY_SEPARATOR, $dir_path);
        $base = DIRECTORY_SEPARATOR;

        foreach ($dirs as $dir) {
            if (!is_dir($base . $dir)) {
                mkdir($base . $dir);
            }

            $base .= $dir . DIRECTORY_SEPARATOR;
        }

        chmod($dir_path, '0775');
    }

    /**
     * Checks the pid file to see if the daemon is already running
     * @return boolean
     */
    public function alreadyRunning() {
        if (file_exists($this->pid_path)) {
            $pid = file_get_contents($this->pid_path);

            if (file_exists("/proc/{$pid}")) {
                echo "Daemon '{$this->daemon->getPidPath()}' is already running with process id {$pid}" . PHP_EOL;

                return true;
            }
        }

        $this->removePidFile();

        return false;
    }

    /**
     * Deletes the pid file for this process
     */
    protected function removePidFile() {
        if (file_exists($this->pid_path)) {
            unlink($this->pid_path);
        }
    }
}
