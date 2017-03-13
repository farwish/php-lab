<?php
/*
class Observer1
{
    public function update()
    {
        echo "logic 1" . PHP_EOL;
    }
}

class Observer2
{
    public function update()
    {
        echo "logic 2" . PHP_EOL;
    }
}


class EventGenerator 
{
    private $obs = [];

    public function addObserver($observer)
    {
        $this->obs[] = $observer;
    }

    public function notify()
    {
        foreach ($this->obs as $observer)
        {
            $observer->update();
        }

        echo "done" . PHP_EOL;
    }
}

class Event extends EventGenerator
{
    public function trigger()
    {
        $this->notify();
    }
}

$ev = new Event;

$ev->addObserver(new Observer1);
$ev->addObserver(new Observer2);

$ev->trigger();
*/

/*
class Event implements SplSubject
{
    private $obs = [];

    public function attach(SplObserver $ob)
    {
        $this->obs[] = $ob; 
    }

    public function detach(SplObserver $ob)
    {
    }

    public function notify()
    {
        foreach ($this->obs as $observer)
        {
            $observer->update($this);
        }
    }
}

class Observer1 implements SplObserver
{
    public function update(SplSubject $subject)
    {
        echo "ob1\n";
    }
}

class Observer2 implements SplObserver
{
    public function update(SplSubject $subject)
    {
        echo "ob2\n";
    }
}

$ev = new Event;
$ev->attach(new Observer1);
$ev->attach(new Observer2);
$ev->notify();
*/

class EventBase implements SplSubject
{
    /**
     * Observer collection.
     *
     * @var \SplObjectStorage
     */
    private $os;

    public function __construct()
    {
        $this->os = new SplObjectStorage();
    }

    /** 
     * attach.
     * 
     */
    public function attach(SplObserver $ob)
    {   
        $this->os->attach($ob);
    }   

    /** 
     * detach.
     *
     */
    public function detach(SplObserver $ob)
    {   
        if ($this->os->contains($ob)) {
            $this->os->detach($ob);
        }
    }   

    /** 
     * notify.
     *
     */
    public function notify()
    {   
        foreach ($this->os as $observer) {
            $observer->update($this);
        }
    }   
}


class Observer1 implements SplObserver
{
    public function update(SplSubject $sub)
    {
        echo "1\n";
    }
}

class Observer2 implements SplObserver
{
    public function update(SplSubject $sub)
    {
        echo "2\n";
    }
}

$ev = new EventBase;
$ob1 = new Observer1;
$ob2 = new Observer2;
$ev->attach($ob1);
$ev->attach($ob2);
$ev->detach($ob2);
$ev->notify();
