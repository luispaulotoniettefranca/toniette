<?php


namespace Source\Support;


use Exception;
use JetBrains\PhpStorm\Pure;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

/**
 * Class Socket
 * @package Source\Support
 */
class Socket implements SocketInterface
{
    /**
     * @var SplObjectStorage
     */
    protected SplObjectStorage $clients;

    /**
     * Socket constructor.
     */
    #[Pure] public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    /**
     * @param ConnectionInterface $conn
     */
    function onOpen(ConnectionInterface $conn): void
    {
        // TODO: Implement onOpen() method.
    }

    /**
     * @param ConnectionInterface $conn
     */
    function onClose(ConnectionInterface $conn): void
    {
        // TODO: Implement onClose() method.
    }

    /**
     * @param ConnectionInterface $conn
     * @param Exception $e
     */
    function onError(ConnectionInterface $conn, Exception $e): void
    {
        // TODO: Implement onError() method.
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    function onMessage(ConnectionInterface $from, $msg): void
    {
        // TODO: Implement onMessage() method.
    }
}