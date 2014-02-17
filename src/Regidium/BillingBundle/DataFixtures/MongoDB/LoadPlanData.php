<?php

namespace Regidium\BillingBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\CommonBundle\Document\Plan;

class LoadPlanData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Base
        $plan = new Plan();
        $plan->setName('Base');
        $plan->setCost(0);
        $plan->setCountChats(10);
        $plan->setCountAgents(2);
        $plan->setType(1);

        $manager->persist($plan);
        $manager->flush($plan);

        $this->addReference('plan_base', $plan);

        // Expanded
        $plan = new Plan();
        $plan->setName('Expanded');
        $plan->setCost(10);
        $plan->setCountChats(100);
        $plan->setCountAgents(20);
        $plan->setType(2);

        $manager->persist($plan);
        $manager->flush($plan);

        $this->addReference('plan_expanded', $plan);

        // Unlimited
        $plan = new Plan();
        $plan->setName('Unlimited');
        $plan->setCost(100);
        $plan->setCountChats(0);
        $plan->setCountAgents(0);
        $plan->setType(3);

        $manager->persist($plan);
        $manager->flush($plan);

        $this->addReference('plan_unlimited', $plan);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}