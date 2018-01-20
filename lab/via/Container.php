<?php
/**
 * Via package.
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

namespace Via;

use Exception;

include "Constants.php";

class Container
{
    /**
     * Worker number.
     *
     * @var int $count
     */
    protected $count = 1;

    /**
     * Internet address or Unix domain.
     *
     * @var string $localSocket
     */
    protected $localSocket = null;

    /**
     * Protocol of socket.
     *
     * @var string $protocol
     */
    protected $protocol = null;

    /**
     * Address of socket.
     *
     * @var string $address
     */
    protected $address = null;

    /**
     * Stream return by stream_socket_server.
     *
     * @var Resource $socketStream
     */
    protected $socketStream = null;

    /**
     * Socket accept timeout (seconds).
     *
     * @var float $timeout
     */
    protected $timeout = 60;

    /**
     * Port of socket.
     *
     * @var int $port
     */
    protected $port = null;

    /**
     * Current instance.
     *
     * @var Object $instance
     */
    protected $instance = null;

    /**
     * Worker process id container.
     *
     * Format likes: [72507 => 72507, 72508 => 72508]]
     *
     * @var array $worker
     */
    protected $pids = [];

    /**
     * Constructor.
     *
     * @param string $socket
     *
     * @return void
     */
    public function __construct(string $socket = '')
    {
        $this->localSocket = $socket ?: null;

        $this->instance    = $this;
    }

    /**
     * Set worker number.
     *
     * @param int $count
     *
     * @return void
     * @throws Exception
     */
    public function setCount(int $count)
    {
        if ((int)$count > 0) {
            $this->count = $count;
        } else {
            throw new Exception('Error: Illegal worker number.' . EOL);
        }
    }

    /**
     * Set socket.
     *
     * Use this function or initialize socket in Constructor.
     *
     * @param string $socket
     *
     * @return void
     */
    public function setSocket(string $socket = '')
    {
        $this->localSocket = $socket ?: null;
    }

    /**
     * Start run.
     *
     * @return void
     */
    public function start()
    {
        //self::createServer();

        self::forkWorkers();

        self::monitorWorkers();
    }

    /**
     * Create socket server.
     *
     * @return void
     * @throws Exception
     */
    protected function createServer()
    {
        if ($this->localSocket) {
            // Unix domain ?
            $list = explode(':', $this->localSocket);
            $this->protocol = $list[0] ?? null;
            $this->address  = $list[1] ? ltrim($list[1], '\/\/') : null;
            $this->port     = $list[2] ?? null;

            // Create a stream context.
            // Options see php.net/manual/en/context.php
            $options = [
                'socket' => [
                    'bindto'        => $this->address . ':' . $this->port,
                    'backlog'       => 1,
                    'so_reuseport'  => true,
                ],
            ];
            $params  = null;
            $context = stream_context_create($options, $params);

            // Create an Internet or Unix domain server socket.
            $errno   = 0;
            $errstr  = '';
            $flags   = ($this->protocol === 'udp') ? STREAM_SERVER_BIND : STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;
            $stream = stream_socket_server($this->localSocket, $errno, $errstr, $flags, $context);
            if (! $stream) {
                throw new Exception("Create socket server fail, errno: {$errno}, errstr: {$errstr}" . EOL);
            }
            $this->socketStream = $stream;

            // Switch to non-blocking mode,
            // affacts calls like fgets and fread that read from the stream.
            if (! stream_set_blocking($stream, false)) {
                throw new Exception('Switch to non-blocking mode fail' . EOL);
            }
        }
    }

    /**
     * Fork workers.
     *
     * @return void
     */
    protected function forkWorkers()
    {
        for ($i = 0; $i < $this->count; $i++) {
            $pid = pcntl_fork();

            switch($pid) {
                case -1:
                    throw new Exception("Fork failed." . EOL);
                    break;
                case 0:
                    // Child process, do business, can exit at last.
                    //sleep(1); $rand = rand(2, 20);
                    //echo "Child postix_getpid " . posix_getpid() . " will spend {$rand} seconds." . EOL;
                    //sleep($rand);

                    //while ( $conn = stream_socket_accept($this->socketStream, -1) ) {
                        //$str = fread($conn, 1024);
                        //fwrite($conn, 'Server say:' . date('Y-m-d H:i:s') . ' ' . $str . EOL);
                    //}


                    die;
                    break;
                default:
                    // Parent(master) process, not do business, cant exit.
                    $this->pids[$pid] = $pid;
                    break;
            }
        }
    }

    /**
     * Monitor child workers that terminated.
     *
     * Wait no hang.
     *
     * @return void
     */
    protected function monitorWorkers()
    {
        do {
            foreach ($this->pids as $pid) {
                $terminated_pid = pcntl_waitpid($pid, $status, WNOHANG);
                if ($terminated_pid > 0) {
                    unset($this->pids[$terminated_pid]);
                    echo "Child {$terminated_pid} terminated." . EOL;
                }
            }
        } while ( count($this->pids) > 0 );
    }
}
