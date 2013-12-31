<?php

namespace Regidium\CommonBundle\Validator\Constraints\UniqueDocument;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueDocument extends Constraint
{
    public $message = 'The value %value% not unique %property%.';

    public $repository;
    public $property;

    public $exclusion;

    public function validatedBy()
    {
        return 'unique_document';
    }

    public function getRequiredOptions()
    {
        return array('repository', 'property');
    }

}
