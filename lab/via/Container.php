<?php
/**
 * Via package.
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

namespace Via;

use Exception;

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
     * Worker process id container.
     *
     * Format likes: [72507 => 72507, 72508 => 72508]]
     *
     * @var array $worker
     */
    protected $pids = [];

    /**
     * Monitored signals.
     *
     * @var array $signals
     */
    protected $signals = [
        SIGINT,  // 2   interrupted by keyboard(ctrl+c)
        SIGQUIT, // 3   quit by keyboard(ctrl+\)
        SIGUSR1, // 10
        SIGUSR2, // 12
        SIGTERM, // 15  terminated by `kill 72507`, and SIGKILL and SIGSTOP can not be catch.
        SIGCHLD, // 17  normal child exit.
    ];

    /**
     * Usable command.
     *
     * @var array $commands
     */
    protected $commands = [
        'start',
        'restart',
        'stop',
    ];

    /**
     * Is in daemon.
     *
     * @var bool $daemon
     */
    protected $daemon = false;

    /**
     * Process title.
     *
     * @var string $title
     */
    protected $title = 'Via process';

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
            throw new Exception('Error: Illegal worker number.' . PHP_EOL);
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
     * Set process title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title)
    {
        if ($title) $this->title = $title;
    }

    /**
     * Start run.
     *
     * @return void
     */
    public function start()
    {
        //self::createServer();

        self::command();

        declare(ticks = 1);
        self::installSignal();

        self::forkWorkers();

        self::monitorWorkers();
    }

    protected function command()
    {
        global $argv;

        $command1 = $argv[1] ?? null;
        $command2 = $argv[2] ?? null;

        if ($command2 && ($command2 === '-d')) {
            $this->daemon = true;
        }

        if (in_array($command1, $this->commands)) {
            switch ($command1) {
                case 'start':
                    break;
                case 'restart':
                    break;
                case 'stop':
                    // need master pid somewhere.
                    break;
                default:
                    break;
            }
        } else {
            echo "Usage:\n    php $argv[0] {start|restart|stop} [-d]" . PHP_EOL;
            die;
        }
    }

    /**
     * Install signal handler.
     *
     * Parent catch the signal and handle.
     *
     * PCNTL signal constants:
     * @see http://php.net/manual/en/pcntl.constants.php
     *
     * @return void
     */
    protected function installSignal()
    {
        foreach ($this->signals as $signal) {
            pcntl_signal($signal, function($signo, $siginfo) {
                switch ($signo) {
                    case SIGINT:
                        $this->kill($signo);
                        break;

                    case SIGQUIT:
                        $this->kill($signo);
                        break;

                    case SIGUSR1:
                        break;

                    case SIGUSR2:
                        break;

                    case SIGTERM:
                        unset($this->portids[posix_getpid()]);
                        break;

                    case SIGCHLD:
                        break;

                    default:
                        break;
                }
            });
        }
    }

    /**
     * Force kill all process to exit.
     *
     * Same to:
     * `ps aux | grep -v grep | grep Via | awk '{print $2}' | xargs kill -9`
     *
     * @return void
     */
    protected function kill($signo)
    {
        if (! $this->daemon) {
            echo "Receive signal number {$signo} " . PHP_EOL;
        }

        // If call current method in child, method will call count+1 times.
        posix_kill(posix_getpid(), SIGKILL);
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
                throw new Exception("Create socket server fail, errno: {$errno}, errstr: {$errstr}" . PHP_EOL);
            }
            $this->socketStream = $stream;

            // Switch to non-blocking mode,
            // affacts calls like fgets and fread that read from the stream.
            if (! stream_set_blocking($stream, false)) {
                throw new Exception('Switch to non-blocking mode fail' . PHP_EOL);
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
        while ( count($this->pids) < $this->count ) {
            $pid = pcntl_fork();

            switch($pid) {
                case -1:
                    throw new Exception("Fork failed." . PHP_EOL);
                    break;
                case 0:
                    // Child process, do business, can exit at last.
                    cli_set_process_title($this->title);

                    sleep(1); $rand = rand(2, 20);
                    echo "Child postix_getpid " . posix_getpid() . " will spend {$rand} seconds." . PHP_EOL;
                    sleep($rand);
                    sleep(30);

                    //while ( $conn = stream_socket_accept($this->socketStream, -1) ) {
                        //$str = fread($conn, 1024);
                        //fwrite($conn, 'Server say:' . date('Y-m-d H:i:s') . ' ' . $str . PHP_EOL);
                    //}

                    die;
                    break;
                default:
                    // Parent(master) process, not do business, cant exit.
                    $this->pids[$pid] = $pid;
                    cli_set_process_title('Master process ' . $this->title);
                    break;
            }
        }
    }

    /**
     * Monitor any child workers that terminated.
     *
     * Wait no hang.
     *
     * @return void
     */
    protected function monitorWorkers()
    {
        do {
            if ($terminated_pid = pcntl_waitpid(-1, $status, WNOHANG)) {

                if (! $this->daemon) {
                    self::debugSignal($terminated_pid, $status);
                }

                unset($this->pids[$terminated_pid]);

                // Normal exited or kill by catched signals, use SIGKILL to force quit.
                if (pcntl_wifexited($status)) {
                    self::forkWorkers();
                }
            }
        } while ( count($this->pids) > 0 );
    }

    /**
     * Output info when child quit.
     *
     * `kill -TERM 80382` as kill 80382
     * `kill -STOP 80382` stop a process to quit.
     * `kill -CONT 80382` continue a process stopped.
     *
     * @param int $status which reference changed by waitpid.
     *
     * @return void
     */
    protected function debugSignal($pid, $status)
    {
        $message = "Child {$pid} terminated, ";

        if (pcntl_wifexited($status)) {
            $message .= "Normal exited with status " . pcntl_wexitstatus($status);
        }

        if (pcntl_wifsignaled($status)) {
            $message .= "Signal killed by signal number " . pcntl_wtermsig($status);
        }

        if (pcntl_wifstopped($status)) {
            $message .= "Signal stopped by signal number " . pcntl_wstopsig($status);
        }

        echo $message . PHP_EOL;
    }
}
