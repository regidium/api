<?php

namespace Regidium\AgentBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Regidium\AgentBundle\Document\Agent;

class LoadAgentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dummyAgent = new Agent();
        $dummyAgent->setFullname('Dummy Agent');
        $dummyAgent->setEmail('dummy.agent@email.com');
        $dummyAgent->setPassword(sha1('123456'));
        $dummyAgent->setStatus(Agent::STATUS_DEFAULT);

        $manager->persist($dummyAgent);
        $manager->flush();

        $this->addReference('dummy-agent', $dummyAgent);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}