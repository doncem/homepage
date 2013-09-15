<?php
namespace script\gearman;

/**
 * Config class based on the registry pattern
 */
abstract class Config {

    /**
     * Collection of closures
     * @var array
     */
    protected $items;

    /**
     * Initiate
     * @param string $host
     */
    public function __construct($host) {
        $this->items = array(
            "gearman_worker" => function() use($host) {
                return new \jukebox\gearman\Worker(new \GearmanWorker(), $host);
            },
            "gearman_client" => function() use($host) {
                return new \jukebox\gearman\Client(new \GearmanClient(), $host);
            }
        );
    }

    /**
     * Get closure from configuration
     * @param string $key
     * @return \Closure
     * @throws Ex
     */
    public function __get($key) {
        if (!array_key_exists($key, $this->items)) {
            throw new Ex("Unable to find configuration '{$key}'", Ex::NO_CONFIGURATION_FOUND);
        }

        if (is_callable($this->items[$key])) {
            return $this->items[$key]();
        }

        return $this->items[$key];
    }

    /**
     * Add closure to configuration
     * @param string $key
     * @param \Closure $value
     */
    public function __set($key, $value) {
        $this->items[$key] = $value;
    }
}
