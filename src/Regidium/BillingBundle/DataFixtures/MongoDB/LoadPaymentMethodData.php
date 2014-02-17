<?php

namespace Regidium\BillingBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\CommonBundle\Document\PaymentMethod;

class LoadPaymentMethodData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Electronic money
        $payment_method = new PaymentMethod();
        $payment_method->setName('Electronic money');
        $payment_method->setType(1);

        $manager->persist($payment_method);
        $manager->flush($payment_method);

        $this->addReference('payment_method_ecash', $payment_method);

        // Plastic card
        $payment_method = new PaymentMethod();
        $payment_method->setName('Plastic card');
        $payment_method->setType(2);

        $manager->persist($payment_method);
        $manager->flush($payment_method);

        $this->addReference('payment_method_plastic_card', $payment_method);

        // Plastic card
        $payment_method = new PaymentMethod();
        $payment_method->setName('Cashless payments');
        $payment_method->setType(3);

        $manager->persist($payment_method);
        $manager->flush($payment_method);

        $this->addReference('payment_method_cashless_payments', $payment_method);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}