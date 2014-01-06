<?php

namespace Regidium\CommonBundle\Validator\Constraints\ExistDocument;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\FormError;

class ExistDocumentValidator extends ConstraintValidator
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Validation
     * @param  array      $value      value
     * @param  Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) return;

        $count = $this->container->get($constraint->repository)->count(array($constraint->property => $value));
        if ($count == 0) {
            $this->context->addViolation($constraint->message, array('%value%' => $value, '%property%' => $constraint->property));
            $error = new FormError(str_replace(['%value%', '%property%'], [$value, $constraint->property], $constraint->message));
            $this->context->getRoot()->addError($error);
        }

    }

}
