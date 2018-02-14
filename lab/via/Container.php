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
     * @var array $pids
     */
    protected $pids = [];

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
    protected $daemon = true;

    /**
     * Monitored signals.
     *
     * @var array $signals
     */
    protected $signals = [
        SIGINT  => 'SIGINT',  // 2   interrupted by keyboard (ctrl+c).
        SIGQUIT => 'SIGQUIT', // 3   quit by keyboard (ctrl+\).
        SIGUSR1 => 'SIGUSR1', // 10
        SIGUSR2 => 'SIGUSR2', // 12
        SIGTERM => 'SIGTERM', // 15  terminated by `kill 72507`, and SIGKILL and SIGSTOP can not be catch.
        SIGCHLD => 'SIGCHLD', // 17  normal child exit.
    ];

    /**
     * Process title.
     *
     * @var string $title
     */
    protected $title = 'Via';

    /**
     * Max client number waited in socket queue.
     *
     * @var int $backlog
     */
    protected $backlog = 100;

    /**
     * Stream return by stream_socket_server.
     *
     * @var Resource $socketStream
     */
    protected $socketStream = null;

    /**
     * Socket select timeout (seconds)
     *
     * @var float $selectTimeout
     */
    protected $selectTimeout = 30;

    /**
     * Socket accept timeout (seconds).
     *
     * @var float $acceptTimeout
     */
    protected $acceptTimeout = 60;

    /**
     * Connection callback function.
     *
     * @var callable $onConnection
     */
    protected $onConnection = null;

    /**
     * Message callback function.
     *
     * @var callable $onMessage
     */
    protected $onMessage = null;

    /**
     * Constructor.
     *
     * Supported socket transports.
     * @see http://php.net/manual/en/transports.php
     *
     * @param string $socket
     */
    public function __construct(string $socket = '')
    {
        $this->localSocket = $socket ?: null;

        $this->onConnection = function() {};
        $this->onMessage    = function() {};
    }

    /**
     * Set worker number.
     *
     * @param int $count
     *
     * @return $this
     * @throws Exception
     */
    public function setCount(int $count)
    {
        if ((int)$count > 0) {
            $this->count = $count;
        } else {
            throw new Exception('Error: Illegal worker process number.' . PHP_EOL);
        }

        return $this;
    }

    /**
     * Set socket.
     *
     * Use this function or initialize socket in Constructor.
     *
     * @param string $socket
     *
     * @return $this
     */
    public function setSocket(string $socket)
    {
        $this->localSocket = $socket;

        return $this;
    }

    /**
     * Set process title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title)
    {
        if ($title) $this->title = $title;

        return $this;
    }

    /**
     * Set socket backlog number.
     *
     * @param int $backlog
     *
     * @return $this
     */
    public function setBacklog(int $backlog)
    {
        $this->backlog = $backlog;

        return $this;
    }

    /**
     * Set select timeout value.
     *
     * @param float $selectTimeout
     *
     * @return $this
     */
    public function setSelectTimeout($selectTimeout)
    {
        $this->selectTimeout = $selectTimeout;

        return $this;
    }

    /**
     * Set accept timeout value.
     *
     * @param float $acceptTimeout
     *
     * @return $this
     */
    public function setAcceptTimeout($acceptTimeout)
    {
        $this->acceptTimeout = $acceptTimeout;

        return $this;
    }

    /**
     * Set connection event callback task.
     *
     * @param callable $callback  the first param is $connection return by accept.
     *
     * @return $this
     */
    public function onConnection(callable $callback)
    {
        $this->onConnection = $callback;

        return $this;
    }

    /**
     * Set message event callback task.
     *
     * @param callable $callback  the first param is $connection, second param is data.
     *
     * @return $this
     */
    public function onMessage(callable $callback)
    {
        $this->onMessage = $callback;

        return $this;
    }

    /**
     * Start run.
     *
     * @throws Exception
     */
    public function start()
    {
        self::strict();

        self::command();

        self::installSignal();

        self::createServer();

        self::forks();

        self::monitor();
    }

    /**
     * Use strict.
     *
     * @throws Exception
     */
    protected function strict()
    {
        if (PHP_MAJOR_VERSION < 7) {
            // Must PHP7.
            throw new Exception("PHP major version must >= 7" . PHP_EOL);
        }

        if (! function_exists('socket_import_stream')) {
            // Must socket extension.
            throw new Exception(
                "Socket extension must be enabled at compile time by giving the '--enable-sockets' option to 'configure'" . PHP_EOL
            );
        }
    }

    /**
     * Parse command and option.
     *
     */
    protected function command()
    {
        global $argv;

        $command = $argv[1] ?? null;
        $stash   = $argv;
        unset($stash[0], $stash[1]);

        // Parse option.
        if ($stash) {
            foreach ($stash as $option) {
                if (! strstr($option, '=')) goto Usage;
                list($k, $v) = explode('=', $option);
                switch ($k) {
                    case '--env':
                        if ($v === 'dev') {
                            $this->daemon = false;
                        } elseif (empty($v) || $v === 'prod') {
                            $this->daemon = true;
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        // Parse command.
        if (in_array($command, $this->commands)) {
            switch ($command) {
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

            Usage:

            echo "Usage:\n    php $argv[0] {start|restart|stop} [Options]" . PHP_EOL;
            echo "Options:" . PHP_EOL;
            echo "    --env=dev       It runs in foreground, show debug message, helpful in developing." . PHP_EOL;
            echo "    --env=prod      This is default choice that runs in daemon, for production environment." . PHP_EOL;
            echo PHP_EOL;
            exit();
        }
    }

    /**
     * Install signal handler in master process.
     *
     * Parent catch the signal, child will extends the signal handler.
     * But it not meens child will receive the signal too, SIGTERM is
     * exception, if parent catch SIGTERM, child will not received, so this
     * signal should be reinstall in the child.
     *
     * If child process terminated, monitor will fork again.
     *
     * PCNTL signal constants:
     * @see http://php.net/manual/en/pcntl.constants.php
     *
     * @throws Exception
     */
    protected function installSignal()
    {
        if (PHP_MINOR_VERSION >= 1) {
            // Low overhead.
            pcntl_async_signals(true);
        } else {
            // A lot of overhead.
            declare(ticks = 1);
        }

        foreach ($this->signals as $signal => $name) {
            pcntl_signal($signal, function($signo, $siginfo) use ($name) {
                if (! $this->daemon) {
                    echo "Pid " . posix_getpid() . " received signal number {$signo} ({$name})" . PHP_EOL;
                }

                switch ($signo) {
                    case SIGINT:
                    case SIGQUIT:
                        // Exit script normally.
                        exit();
                        break;

                    case SIGUSR1:
                        break;

                    case SIGUSR2:
                        break;

                    case SIGTERM:
                        // If parent catch the signal, it will cause block.
                        // So child need reinstall the handler.
                        pcntl_signal(SIGTERM, SIG_DFL);
                        break;

                    case SIGCHLD:
                        pcntl_signal(SIGCHLD, SIG_DFL);
                        break;

                    case SIGPIPE:
                        echo "Catch sigpipe." . PHP_EOL;
                        break;

                    default:
                        break;
                }
            });
        }
    }

    /**
     * Install signal handler in child process.
     *
     */
    protected function installChildSignal()
    {
        foreach ($this->signals as $signal => $name) {
            switch ($signal) {
                case SIGTERM:
                    // If parent catch the signal, it will cause block.
                    // So child need reinstall the handler.
                    pcntl_signal(SIGTERM, SIG_DFL);
                    break;

                case SIGCHLD:
                    pcntl_signal(SIGCHLD, SIG_DFL);
                    break;

                case SIGPIPE:
                    echo "Catch sigpipe." . PHP_EOL;
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Create socket server.
     *
     * Master create socket and listen, later on descriptor can be used in child.
     * If reuse port, child can create server by itself.
     *
     * @throws Exception
     */
    protected function createServer()
    {
        if ($this->localSocket) {
            // Parse socket name, Unix domain ?
            $list = explode(':', $this->localSocket);
            $this->protocol = $list[0] ?? null;
            $this->address  = $list[1] ? ltrim($list[1], '\/\/') : null;
            $this->port     = $list[2] ?? null;

            // Create a stream context.
            // Options see http://php.net/manual/en/context.socket.php
            // Available socket options see http://php.net/manual/en/function.socket-get-option.php
            $options = [
                'socket' => [
                    'bindto'        => $this->address . ':' . $this->port,
                    'backlog'       => $this->backlog,
                    'so_reuseport'  => true,
                ],
            ];
            $params  = null;
            $context = stream_context_create($options, $params);

            // Create an Internet or Unix domain server socket.
            $errno   = 0;
            $errstr  = '';
            $flags   = ($this->protocol === 'udp') ? STREAM_SERVER_BIND : STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;
            $this->socketStream  = stream_socket_server($this->localSocket, $errno, $errstr, $flags, $context);
            if (! $this->socketStream) {
                throw new Exception("Create socket server fail, errno: {$errno}, errstr: {$errstr}" . PHP_EOL);
            }

            // More socket option, must install sockets extension.
            $socket = socket_import_stream($this->socketStream);

            if ($socket !== false && $socket !== null) {
                // Predefined constants: http://php.net/manual/en/sockets.constants.php
                // Level number see: http://php.net/manual/en/function.getprotobyname.php; Or `php -r "print_r(getprotobyname('tcp'));"`
                // Option name see: http://php.net/manual/en/function.socket-get-option.php
                socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE, 1);
                socket_set_option($socket, SOL_TCP, TCP_NODELAY, 1);
            }

            // Switch to non-blocking mode,
            // affacts calls like fgets and fread that read from the stream.
            if (! stream_set_blocking($this->socketStream, false)) {
                throw new Exception('Switch to non-blocking mode fail' . PHP_EOL);
            }

            // Store current process his socket stream.
            $this->read[]  = $this->socketStream;
            $this->write[] = $this->socketStream;
            $this->except  = [];
        }
    }

    /**
     * Fork workers until reach 'count' number.
     *
     */
    protected function forks()
    {
        // Parent(master) process, set title.
        cli_set_process_title('Master process ' . $this->title);

        while ( count($this->pids) < $this->count ) {
            self::forkOne();
        }
    }

    /**
     * To set all descriptor passed into stream_select.
     *
     * So separate pcntl_fork and stream_select.
     *
     */
    protected function forkOne()
    {
        $pid = pcntl_fork();

        switch($pid) {
            case -1:
                throw new Exception("Fork failed." . PHP_EOL);
                break;
            case 0:
                // Child process, do business, can exit at last.
                cli_set_process_title($this->title);

                self::installChildSignal();

                self::poll();

                exit();
                break;
            default:
                // Parent(master) process, not do business, cant exit.
                $this->pids[$pid] = $pid;
                break;
        }
    }

    /**
     * Poll on all child process.
     *
     */
    protected function poll()
    {
        // Store child socket stream.
        $this->read[]  = $this->socketStream;
        $this->write[] = $this->socketStream;
        $this->except  = [];

        do {
            // Stream_select need variable reference, so reassignment.
            $read = $this->read;
            $write = $this->write;
            $except = $this->except;

            // I/O multiplexing.
            // Warning raised if select system call is interrupted by an incoming signal,
            // timeout will be zero and FALSE on error.
            $value = @stream_select($read, $write, $except, $this->selectTimeout);

            if ($value > 0) {

                foreach ($this->read as $socketStream) {

                    // TODO: Timout set to zero or not.
                    // Client number greater than process count will cause status pending, so just connect cant do anything!
                    // Heartbeat mechanism, need timer.
                    // Remote address is user ip:port.
                    if (false !== ($connection = @stream_socket_accept($socketStream, 0, $remote_address))) {

                        // Connect success, callback trigger.
                        call_user_func($this->onConnection, $connection);

                        // Loop prevent read once in callback.
                        call_user_func_array($this->onMessage, [$connection]);
                    }
                }
            } elseif ($value === 0 || $value === false) {
                // Timeout or Error
                continue;
            } else {
            }
        } while (true);
    }

    /**
     * Monitor any child process that terminated.
     *
     */
    protected function monitor()
    {
        // Block master, use WNOHANG in loop will waste too much CPU.
        while ($terminated_pid = pcntl_waitpid(-1, $status, 0)) {

            if (! $this->daemon) {
                self::debugSignal($terminated_pid, $status);
            }

            unset($this->pids[$terminated_pid]);

            // Fork again condition: normal exited or killed by SIGTERM.
            if ( pcntl_wifexited($status) ||
                (pcntl_wifsignaled($status) && in_array(pcntl_wtermsig($status), [SIGTERM]) )
            ) {
                self::forks();
            }
        }
    }

    /**
     * Output info when child quit.
     *
     * `kill -TERM 80382` as kill 80382
     * `kill -STOP 80382` stop a process to quit.
     * `kill -CONT 80382` continue a process stopped.
     *
     * @param int $status which reference changed by waitpid.
     */
    protected function debugSignal($pid, $status)
    {
        $message = "Child process {$pid} terminated, ";

        if (pcntl_wifexited($status)) {
            $message .= "Normal exited with status " . pcntl_wexitstatus($status);
        }

        if (pcntl_wifsignaled($status)) {
            $message .= "Signal killed by signal number " . pcntl_wtermsig($status) . " (" . ($this->signals[ pcntl_wtermsig($status) ] ?? 'Unknow') . ")";
        }

        if (pcntl_wifstopped($status)) {
            $message .= "Signal stopped by signal number " . pcntl_wstopsig($status);
        }

        echo $message . PHP_EOL;
    }
}
