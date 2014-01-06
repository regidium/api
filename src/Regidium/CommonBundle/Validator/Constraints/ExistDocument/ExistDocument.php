<?php

namespace Regidium\CommonBundle\Validator\Constraints\ExistDocument;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ExistDocument extends Constraint
{
    public $message = 'On the specified criteria (%property% = %value%) document was not found.';

    public $repository;
    public $property;

    public $exclusion;

    public function validatedBy()
    {
        return 'exist_document';
    }

    public function getRequiredOptions()
    {
        return array('repository', 'property');
    }

}
