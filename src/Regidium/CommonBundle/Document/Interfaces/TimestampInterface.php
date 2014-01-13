<?php

namespace Regidium\CommonBundle\Document\Interfaces;

interface TimestampInterface
{

    public function getCreated();
    public function setCreated($created);

    public function getUpdated();
    public function setUpdated($updated);

}
