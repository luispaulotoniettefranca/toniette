<?php


namespace Source\Support;


use Ratchet\ConnectionInterface;

/**
 * Interface SocketInterface
 * @package Source\Support
 */
interface SocketInterface
{
    /**
     * @param ConnectionInterface $conn
     * @return mixed
     */
    public function onOpen(ConnectionInterface $conn): void;

    /**
     * @param ConnectionInterface $from
     * @param $msg
     * @return mixed
     */
    public function onMessage(ConnectionInterface $from, $msg): void;

    /**
     * @param ConnectionInterface $conn
     * @return mixed
     */
    public function onClose(ConnectionInterface $conn): void;

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $exception
     * @return mixed
     */
    public function onError(ConnectionInterface $conn, \Exception $exception): void;
}