<?php
namespace jukebox\gearman;

class Client extends \script\gearman\Client {

    /**
     * Push queued tracks to gearman worker
     * @param array $tracks
     * @throws _gearman\Ex
     */
    public function setQueue(array $tracks) {
        $this->connect();
        $this->client->doBackground(
            Worker::FUNC_SET_QUEUE,
            json_encode(array(
                "tracks" => $tracks
            ))
        );

        if ($this->client->returnCode() != GEARMAN_SUCCESS) {
            throw new _gearman\Ex(
                "Failed to add gearman task to '" . Worker::FUNC_SET_QUEUE . "'",
                $this->client->returnCode()
            );
        }
    }
}
