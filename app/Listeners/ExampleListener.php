<?php

namespace App\Listeners;

use SplObserver;
use SplSubject;

class YeniMesajPosta extends Listeners implements SplObserver
{
    public function update(SplSubject $SplSubject)
    {
    }
}
