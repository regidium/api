<?php

namespace Regidium\FileBundle\Handler;

use Regidium\UserBundle\Document\File;

interface FileHandlerInterface
{
    /**
     * Get File given criteria
     *
     * @param array $criteria
     *
     * @return array
     */
    public function get(array $criteria);
}