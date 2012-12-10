<?php

use xframe\registry\Registry;

class MemcacheSingleton {

    private static $instance;
    private $memcache;
    private $registry;
    private $connected = false;
    private $prefix_namespace = true;
    private $prefix = null;
    private $log = array();
    
    /**
     * Initilaises the MemcacheSingleton
     * @param Registry $registry
     */
    private function __construct(Registry $registry) {
        $this->memcache = new Memcached();
        $this->registry = $registry;
        $this->log = array(
            "get" => array("Namespace\tIdentifier\tKey"),
            "set" => array("Namespace\tIdentifier\tKey\tMax Cache Time")
        );
        $this->connect();
    }
    
    /**
     * Connects to servers 
     * @return boolean
     */
    public function connect() {
        if (!$this->connected) {
            $this->memcache->addServer(
                $this->registry->get("MEMCACHE_HOST"),
                (int)$this->registry->get("MEMCACHE_HOST")
            );
            $this->connected = true;
            /*foreach ($this->server_pool as $server) {
                if ($this->memcache->addServer($server->address, $server->port)) {
                    $this->connected = true;
                }
            }*/
        }
        
        return $this->connected;
    }
    
    /**
     * Is connection established
     * @return boolean
     */
    public function isConnected() {
        return $this->connected;
    }
    
    /**
     * Initialises the memcache singleton with a server list
     * @param Registry $registry
     * @return MemcacheSingleton
     */
    public static function instance(Registry $registry) {
        if (!isset(self::$instance)) {
            self::$instance = new MemcacheSingleton($registry);
        }
        
        return self::$instance;
    }

    /**
     * Sets the prefix
     * @param string $prefix
     * @return void
     */
    public function set_prefix($prefix) {
        $this->prefix = $prefix;
    }
    
    public function get_namespace_prefix() {
        $ns_prefix = "";
        
        if (defined("CONFIG")) $ns_prefix .= CONFIG . "_";
        
        if (!empty($this->prefix)) {
            $ns_prefix .= $this->prefix . "_";
        }
        
        return $ns_prefix;
    }

    /**
     * Returns the current prefix
     * @return string
     */
    public function get_prefix() {
        return $this->prefix;
    }
    
    /**
     * Gets a value out of memcache
     * @param string $namespace
     * @param string $identifier
     * @return mixed false on cache miss
     */
    public function get($namespace, $identifier) {
        $content = false;
        
        if ($this->prefix_namespace === true) {
            $namespace = strtolower($this->get_namespace_prefix() . $namespace);
        }
        
        if ($this->connected) {
            $key = $this->get_namespaced_key($namespace, $identifier);
        
            /*if (defined("MEMCACHE_LOGGING") && MEMCACHE_LOGGING) {
                $this->log_get($namespace, $identifier, $key);
            }*/
            
            $cached_object = $this->memcache->get($key);
            
            if ($cached_object) {
                $expiry = $cached_object["expiry"];
            
                // if object is expired, update the expiry date because we don't want anyone else updating this object
                // until we can set what the actual new value is
                // eg. person1 will get false and repopulate the correct value but in the mean time 100 others could be querying
                // so rather than generate 100 extra db queries, we just get a few people with potentially the wrong value
                if ($expiry <= time()) {
                    //only keep object in cache for another minute, to let the cached item be repopulated and to prevent cache rushing
                    $cached_oject["expiry"] = $max_cache_time = time() + 60;
                    $this->memcache->replace($key, $cached_oject, $max_cache_time);
                } else {
                    $content = $cached_object["content"];
                }
            }
        }
        
        return $content;
    }
    
    /**
     * Sets a value in memcache for a set time
     * @param string $namespace
     * @param string $identifier
     * @param mixed $content
     * @param int $expire_time [optional] Default 86400 = 1 day
     * @return boolean
     */
    public function set($namespace, $identifier, $content, $expire_time = 86400) {
        $cached = false;
        
        if ($this->prefix_namespace === true) {
            $namespace = strtolower($this->get_namespace_prefix() . $namespace);
        }

        if ($this->connected) {
            $key = $this->get_namespaced_key($namespace, $identifier);
            //store the expire time in the cached object to prevent cache rushes when items expire
            $expiry = time() + $expire_time;
            $max_cache_time = $expiry + $expire_time;
        
            /*if (defined("MEMCACHE_LOGGING") && MEMCACHE_LOGGING) {
                $this->log_set($namespace, $identifier, $key, $max_cache_time);
            }*/
            
            $to_cache = array();
            $to_cache["cached"] = time();
            $to_cache["expiry"] = $expiry;
            $to_cache["content"] =  $content;

            if (!$this->memcache->replace($key, $to_cache, $max_cache_time)) {
                if ($this->memcache->add($key, $to_cache, $max_cache_time)) {
                    $cached = true;
                }
            } else {
                $cached = true;
            }
        }
        
        return $cached;
    }
    
    /**
     * Expires an item or entire namespace in memcache
     * @param string $namespace
     * @param string $identifier
     * 
     */
    public function expire($namespace, $identifier = null) {
        if ($this->connected) {
            if ($this->prefix_namespace === true) {
                $namespace = strtolower($this->get_namespace_prefix() . $namespace);
            }
            
            if ($identifier == null) {
                return $this->memcache->increment($namespace . "_key");
            } else {
                $namespace_key = $this->memcache->get($namespace . "_key");
                $key = md5($namespace . "_" . $namespace_key . "_" . $identifier);
                
                return $this->memcache->delete($key);
            }
        } else {
            return false;
        }
    }
    
    /**
     * Get statistics from all servers in pool
     * @return array see http://www.php.net/manual/en/memcache.getextendedstats.php
     */
    public function get_status() {
        return $this->memcache->getStats();
    }
    
    /**
     * Returns a key for namespace/identifier pair
     * @param string $namespace
     * @param string $identifier
     * @return mixed false on error or md5 string on success
     */
    public function get_namespaced_key($namespace, $identifier) {
        if ($this->connected) {
            try {
                // the unique namespace key allows us to expire entire namespaces
                $namespace_key = $this->memcache->get($namespace . "_key");
            } catch (Exception $ex) {
                $namespace_key = false;
            }
            
            // if not set, initialize it
            if ($namespace_key === false) {
                $namespace_key = mt_rand(1, 10000);
                $this->memcache->set($namespace . "_key", $namespace_key);
            }
            
            // we use md5 because there is a 255 char limit in the key
            return md5($namespace . "_" . $namespace_key . "_" . $identifier);
        } else {
            return false;
        }
    }
    
    /**
     * Log it
     * @param string $namespace
     * @param string $identifier
     * @param string $key 
     */
    private function log_get($namespace, $identifier, $key) {
        $this->log['get'][] = "{$namespace}\t{$identifier}\t{$key}";
    }
    
    /**
     * Log it
     * @param string $namespace
     * @param string $identifier
     * @param string $key
     * @param int $max_cache_time 
     */
    private function log_set($namespace, $identifier, $key, $max_cache_time) {
        $this->log['set'][] = "{$namespace}\t{$identifier}\t{$key}\t{$max_cache_time}";
    }
    
    public function get_log() {
        return $this->log;
    }
    
    /**
     * Gets and sets if not there
     * @param string $namespace the memcache namespace
     * @param string $identifier the memcache key
     * @param Closure $code The code that produces the value for the set
     * @param int $expire_time Time to live [optional] Default 86400 = 1 day
     * @return mixed $val
     */
    public function get_and_set($namespace, $identifier, Closure $code, $expire_time = 86400) {
        $val = self::$instance->get($namespace, $identifier);
        if ($val === false) {
            $val = $code();
            self::$instance->set($namespace, $identifier, $val, $expire_time);
        }
        
        return $val;
    }
    
    public function get_memcache() {
        return $this->memcache;
    }
}
