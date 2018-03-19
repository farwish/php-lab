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
     * Child process number.
     *
     * Master has one child process by default.
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
     * Stream return by stream_socket_server.
     *
     * @var Resource $socketStream
     */
    protected $socketStream = null;

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
     * Process title.
     *
     * @var string $processTitle
     */
    protected $processTitle = 'Via';

    /**
     * Max client number waited in socket queue.
     *
     * @var int $backlog
     */
    protected $backlog = 100;

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
     * The path of file saved ppid.
     *
     * @var string $ppidPath
     */
    protected $ppidPath = '/tmp';

    /**
     * Parent process id
     *
     * @var int $ppid
     */
    protected $ppid = null;

    /**
     * Child process id container.
     *
     * Format likes: [ 72506 => [ 72507 => 72507, 72508 => 72507 ] ]
     *               [ ppid  => [  pid1 =>  pid1,  pid2 => pid2  ] ]
     *
     * @var array $pids
     */
    protected $pids = [];

    /**
     * Monitored signals.
     *
     * Tip: If processes stopped by SIGSTOP(ctrl+z), use `ps auxf | grep -v grep | grep Via | awk '{print $2}' | xargs kill -CONT`
     * recover from `T` to `S`.
     *
     * @var array $signals
     */
    protected $signals = [
        SIGINT  => 'SIGINT',  // 2   interrupted by keyboard (ctrl+c).
        SIGQUIT => 'SIGQUIT', // 3   quit by keyboard (ctrl+\).
        SIGUSR1 => 'SIGUSR1', // 10  custom
        SIGUSR2 => 'SIGUSR2', // 12  custom
        SIGPIPE => 'SIGPIPE', // 13  write to broken pipe emit it and process exit.
        SIGTERM => 'SIGTERM', // 15  terminated by `kill pid`, note that SIGKILL(9) and SIGSTOP(19) cant be caught.
        SIGCHLD => 'SIGCHLD', // 17  exited normal between one child.
    ];

    /**
     * Server information.
     *
     * @var array
     */
    protected $serverInfo = [
        'start_file'     => '',
        'pid_file'       => '',
    ];

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
     * Set child process number.
     *
     * @param int $count
     *
     * @return $this
     * @throws Exception
     */
    public function setCount(int $count) : Container
    {
        if ((int)$count > 0) {
            $this->count = $count;
        } else {
            throw new Exception('Error: Illegal child process number.' . PHP_EOL);
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
    public function setSocket(string $socket): Container
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
    public function setProcessTitle(string $title): Container
    {
        if ($title) $this->processTitle = $title;

        return $this;
    }

    /**
     * Set the path of file saved ppid.
     *
     * @param string $path
     *
     * @return Container
     */
    public function setPpidPath(string $path): Container
    {
        $this->ppidPath = $path;

        return $this;
    }

    /**
     * Set socket backlog number.
     *
     * @param int $backlog
     *
     * @return $this
     */
    public function setBacklog(int $backlog): Container
    {
        $this->backlog = $backlog;

        return $this;
    }

    /**
     * Set select timeout value.
     *
     * @param int $selectTimeout
     *
     * @return $this
     */
    public function setSelectTimeout(int $selectTimeout): Container
    {
        $this->selectTimeout = $selectTimeout;

        return $this;
    }

    /**
     * Set accept timeout value.
     *
     * @param int $acceptTimeout
     *
     * @return $this
     */
    public function setAcceptTimeout(int $acceptTimeout): Container
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
    public function onConnection(callable $callback): Container
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
    public function onMessage(callable $callback): Container
    {
        $this->onMessage = $callback;

        return $this;
    }

    /**
     * Start run.
     *
     * @throws Exception
     */
    public function start(): void
    {
        self::command();

        self::initializeMaster();

        self::createServer();

        self::forkAll();

        self::monitor();
    }

    /**
     * Use strict.
     *
     * @throws Exception
     */
    protected function strict(): void
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
     * @throws Exception
     */
    protected function command(): void
    {
        global $argv;

        self::strict();

        $command = $argv[1] ?? null;
        $stash   = $argv;
        unset($stash[0], $stash[1]);

        // Parse option.
        if ($stash) {
            foreach ($stash as $option) {
                if ($option == '-dev') {
                    $this->daemon = false;
                }
                // Others...
            }
        }

        // Parse command.
        if (in_array($command, $this->commands)) {
            switch ($command) {
                case 'start':
                    // Default.
                    break;
                case 'restart':
                    // Do some thing.
                    break;
                case 'stop':
                    // Do some thing.
                    // need master pid somewhere.
                    break;
                default:
                    break;
            }
        } else {
            echo PHP_EOL;
            echo "Usage:" . PHP_EOL;
            echo "    php {$argv[0]} {start|restart|stop} [Options]" . PHP_EOL;
            echo "Command:" . PHP_EOL;
            echo "    start         Start run server side process." . PHP_EOL;
            echo "    restart       Restart run server side process." . PHP_EOL;
            echo "    stop          Stop all running process start before." . PHP_EOL;
            echo "Options:" . PHP_EOL;
            echo "    -dev          Run in foreground, show debug message, helpful in developing; without it will runs in daemon by default." . PHP_EOL;
            echo PHP_EOL;
            exit();
        }
    }

    /**
     * Initialzie master process.
     *
     * Parent catch the signal, child will extends parent signal handler.
     * But it not means child will receive the signal too, SIGTERM is
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
    protected function initializeMaster(): void
    {
        if (PHP_MINOR_VERSION >= 1) {
            // Low overhead.
            pcntl_async_signals(true);
        } else {
            // A lot of overhead.
            declare(ticks = 1);
        }

        // Initialize process info.
        $this->ppid = posix_getpid();
        $this->pids[$this->ppid] = [];
        $this->serverInfo['start_file'] = debug_backtrace()[1]['file'];
        $this->serverInfo['pid_file'] = rtrim($this->ppidPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
                                        . str_replace([DIRECTORY_SEPARATOR, '.'], ['_', '_'], $this->serverInfo['start_file']) . '.pid';

        cli_set_process_title("Master process {$this->processTitle}, start file {$this->serverInfo['start_file']}");

        if (! file_exists($this->serverInfo['pid_file'])) {
            touch($this->serverInfo['pid_file']);
        } else {
            $_ppid = (int)file_get_contents($this->serverInfo['pid_file']);
            if ($_ppid && ! posix_getsid($_ppid)) {
                // If valid.
                throw new Exception("(Already running, start file {$this->serverInfo['start_file']})");
            }
        }
//        file_put_contents($this->serverInfo['pid_file'], $this->ppid);

        // TODO: notice child to quit too when parent quited.
        // TODO: when all child quit, delete pid file.
    }

    /**
     * Install signal handler in child process.
     *
     * If child process terminated, monitor will fork again.
     *
     * PCNTL signal constants:
     * @see http://php.net/manual/en/pcntl.constants.php
     *
     * @throws Exception
     */
    protected function installChildSignal(): void
    {
        $return_value = true;
        foreach ($this->signals as $signo => $name) {
            // Will extend parent handler first.
            switch ($signo) {
                case SIGUSR1:

                    break;
                case SIGUSR2:

                    break;
                case SIGINT:
                case SIGQUIT:
                case SIGTERM:
                case SIGCHLD:
                case SIGPIPE:
//                    $return_value = pcntl_signal($signo, function($signo, $siginfo) {
//                        exit();
//                    });
                    $return_value = pcntl_signal($signo, SIG_DFL);
                    break;
                default:
                    break;
            }

            if (! $return_value) {
                throw new Exception('Install signal failed.' . PHP_EOL);
            }
        }
        unset($return_value);
    }

    /**
     * Create socket server.
     *
     * Master create socket and listen, later on descriptor can be used in child.
     * If reuse port, child can create server by itself.
     *
     * @throws Exception
     */
    protected function createServer(): void
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
     * Fork child process until reach 'count' number.
     *
     * @throws Exception
     */
    protected function forkAll(): void
    {
        while ( count($this->pids[$this->ppid]) < ($this->count) ) {
            self::fork();
        }
    }

    /**
     * To set all descriptor passed into stream_select.
     *
     * So separate pcntl_fork and stream_select.
     * Install signal in child.
     *
     * @throws Exception
     */
    protected function fork(): void
    {
        $pid = pcntl_fork();

        switch($pid) {
            case -1:
                throw new Exception("Fork failed." . PHP_EOL);
                break;
            case 0:
                // Child process, do business, can exit at last.
                cli_set_process_title("Child process {$this->processTitle}");

                self::installChildSignal();

                self::poll();

                exit();
                break;
            default:
                // Parent(master) process, not do business, cant exit.
                $this->pids[$this->ppid][$pid] = $pid;
                break;
        }
    }

    /**
     * Poll on all child process.
     *
     */
    protected function poll(): void
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
     * If child exited or terminated, fork one.
     *
     * @throws Exception
     */
    protected function monitor(): void
    {
        // Block on master, use WNOHANG in loop will waste too much CPU.
        while ($terminated_pid = pcntl_waitpid(-1, $status, 0)) {

            if (! $this->daemon) {
                self::debugSignal($terminated_pid, $status);
            }

            unset($this->pids[$this->ppid][$terminated_pid]);

            self::forkAll();

            // Fork again condition: normal exited or killed by SIGTERM.
//            if ( pcntl_wifexited($status) || (pcntl_wifsignaled($status) && in_array(pcntl_wtermsig($status), [SIGTERM])) ) {
//                self::forkAll();
//            }
        }
    }

    /**
     *
     * @throws Exception
     */
    protected function checkMasterAlive()
    {
        $this->ppid = posix_getpid();
        $this->pids[$this->ppid] = [];
        $this->serverInfo['start_file'] = debug_backtrace()[1]['file'];
        $this->serverInfo['pid_file'] = rtrim($this->ppidPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace([DIRECTORY_SEPARATOR, '.'], ['_', '_'], $this->serverInfo['start_file']) . '.pid';

        cli_set_process_title("Master process {$this->processTitle}, start file {$this->serverInfo['start_file']}");

        if (! file_exists($this->serverInfo['pid_file'])) {
            touch($this->serverInfo['pid_file']);
        } else {
            $_ppid = (int)file_get_contents($this->serverInfo['pid_file']);
            if ($_ppid && ! posix_getsid($_ppid)) {
                // If valid.
                throw new Exception("(Already running, start file {$this->serverInfo['start_file']})");
            }
        }
    }

    /**
     * Output info when child quit.
     *
     * `kill -TERM 80382` as kill 80382
     * `kill -STOP 80382` stop a process, use -CONT to recover.
     * `kill -CONT 80382` continue a process stopped.
     *
     * @param $pid
     * @param $status
     */
    protected function debugSignal($pid, $status): void
    {
        $other_debug_signals = [
            SIGKILL => 'SIGKILL',
        ];

        $message = "Process[{$pid}] quit, ";

        if (pcntl_wifexited($status)) {
            $message .= "Normal exited with status " . pcntl_wexitstatus($status) . ", line " . __LINE__;
        }

        if (pcntl_wifsignaled($status)) {
            $message .= "by signal " .
                ($this->signals[ pcntl_wtermsig($status) ] ?? ($other_debug_signals[pcntl_wtermsig($status)] ?? 'Unknow')) . "(" . pcntl_wtermsig($status) . "), line " . __LINE__;
        }

        if (pcntl_wifstopped($status)) {
            $message .= "by signal (" .
                pcntl_wstopsig($status) . "), line " . __LINE__;
        }

        echo $message . PHP_EOL;
    }
}
