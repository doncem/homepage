<?php
namespace script\gearman;

/**
 * Gearman client class
 * @package script_gearman
 */
abstract class Client {

    /**
     * Gearman server host
     * @var strin
     */
    protected $host;

    /**
     * Gearman server port
     * @var int
     */
    protected $port;

    /**
     * Client
     * @var \GearmanClient
     */
    protected $client;

    /**
     * Initiate
     * @param \GearmanClient $client
     * @param string $host
     * @param int $port
     */
    public function __construct(\GearmanClient $client, $host, $port = 4730) {
        $this->client = $client;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Connects to gearman server
     * @throws Ex
     */
    protected function connect() {
        if ($this->client->addServer($this->host, $this->port) === false) {
            throw new Ex(
                "Unable to connect to Gearman at '{$this->host}:{$this->port}'",
                Ex::CONNECTION_ERROR
            );
        }
    }
}
