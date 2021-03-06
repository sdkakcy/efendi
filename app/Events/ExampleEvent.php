<?php

namespace App\Events;

use SplObserver;
use SplSubject;

class ExampleEvent implements SplSubject
{
    protected $observers = [];

    public function __construct()
    {
    }

    public function attach(SplObserver $observer)
    {
        $key = spl_object_hash($observer);
        $this->observers[$key] = $observer;

        return $this;
    }


    public function detach(SplObserver $observer)
    {
        $key = spl_object_hash($observer);
        unset($this->observers[$key]);
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}