<?php

namespace Regidium\CommonBundle\Document\Interfaces;

interface PeriodInterface
{

    public function getStarted();
    public function setStarted($started);

    public function getEnded();
    public function setEnded($ended);

}
