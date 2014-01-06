<?php

namespace Regidium\CommonBundle\Document\Interfaces;

interface PreiodableInterface
{

    public function getStarted();
    public function setStarted($started);

    public function getEnded();
    public function setEnded($ended);

}
