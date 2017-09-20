<?php

class Timer
{
    private $queue;

    private $timer_id = 1;

    private $event_timer;

    private $select_timeout;

    public function __construct()
    {
        $this->queue = new \SplPriorityQueue();
        $this->queue->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
    }

    public function add($func, $args = [])
    {
        while ( $this->timer_id < 5 ) {
        
            $timer_id = $this->timer_id++;
            $run_time = microtime(true) + 3600 + $timer_id; 
            $this->queue->insert( $timer_id, -$run_time );

            $this->event_timer[$timer_id] = [
                $func, $args
            ];

            return $this->timer_id;
        }
    }

    public function getQueue()
    {
        return $this->queue;
    }

    public function getTimerId()
    {
        return $this->timer_id;
    }
}

$obj = new Timer();



print_r( $obj->getQueue() );

print_r( $obj->getTimerId() . PHP_EOL);

$task = [
    [
        'data' => 1,
        'priority' => 1505795207,
    ],
    [
        'data' => 2,
        'priority' => 1505795206,
    ]
];

foreach ($task as $arr) {
    $obj->getQueue()->insert($arr['data'], $arr['priority']);
}

$ret = $obj->getQueue()->count();

print_r($ret);
