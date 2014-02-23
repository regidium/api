<?php

namespace Regidium\CommonBundle\Validator\Constraints\UniqueDocument;

use Symfony\Component\Validator\Constraint;

class UniqueDocument extends Constraint
{
    /**
     * @var string
    */
    public $message = 'The value %value% not unique %property%.';

    public $repository;
    public $property;

    /**
     * @var mixed
     */
    public $exclusion;

    public function validatedBy()
    {
        return 'unique_document';
    }

    public function getRequiredOptions()
    {
        return ['repository', 'property'];
    }

}
