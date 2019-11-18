<?php


namespace App\Controller;

class timerUpdate
{
    private $intervalDays=7;
    private $lastUpdate;

    public function __construct()
    {
        $this->lastUpdate=new \DateTime('2019-11-18 00:00:00');
    }

    public function timer(){
        $now=new \DateTime();
        if((int)$now->diff($this->lastUpdate,true)->format('%d')%$this->intervalDays==0 and $now->diff($this->lastUpdate,true)->format("%h%i")=="0000"){
            $this->lastUpdate=$now;
            return true;
        }
        else{
            return false;
        }
    }
}