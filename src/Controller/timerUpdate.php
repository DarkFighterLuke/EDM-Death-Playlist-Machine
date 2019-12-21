<?php

namespace App\Controller;

class timerUpdate
{
    private $intervalDays = 7;
    private $lastUpdate;

    public function __construct()
    {
        $this->lastUpdate = new \DateTime('2019-11-15 00:00:00');
    }

    public function timer()
    {
        $now = new \DateTime();
        if ($this->days_diff($now, $this->lastUpdate) % $this->intervalDays == 0) {
            $this->lastUpdate = $now;
            error_log("true", 0);
            return true;
        } else {
            error_log("false", 0);
            return false;
        }
    }
    
    function days_diff($d1, $d2) {
        $x1 = $this->days($d1);
        $x2 = $this->days($d2);
       
        if ($x1 && $x2) {
            return abs($x1 - $x2);
        }
    }
    
    function days($x) {
        if (get_class($x) != 'DateTime') {
            return false;
        }
       
        $y = $x->format('Y') - 1;
        $days = $y * 365;
        $z = (int)($y / 4);
        $days += $z;
        $z = (int)($y / 100);
        $days -= $z;
        $z = (int)($y / 400);
        $days += $z;
        $days += $x->format('z');
    
        return $days;
    }
}