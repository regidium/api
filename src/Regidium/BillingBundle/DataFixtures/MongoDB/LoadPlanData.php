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
        $plan->setCountAgents(1);
        $plan->setType(1);

        $manager->persist($plan);
        $manager->flush($plan);

        $this->addReference('plan_base', $plan);

        // Expanded
        $plan = new Plan();
        $plan->setName('Company');
        $plan->setCost(500);
        $plan->setCountAgents(1);
        $plan->setType(2);

        $manager->persist($plan);
        $manager->flush($plan);

        $this->addReference('plan_company', $plan);

        // Unlimited
        $plan = new Plan();
        $plan->setName('Corporation');
        $plan->setCost(550);
        $plan->setCountAgents(1);
        $plan->setType(3);

        $manager->persist($plan);
        $manager->flush($plan);

        $this->addReference('plan_orporation', $plan);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}