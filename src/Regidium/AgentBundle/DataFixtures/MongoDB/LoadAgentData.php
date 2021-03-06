<?php

namespace Regidium\AgentBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\CommonBundle\Document\Agent;

class LoadAgentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dummyAgent = new Agent();
        $dummyAgent->setFirstname('Степан');
        $dummyAgent->setLastname('Петрович');
        $dummyAgent->setEmail('dummy.agent@email.com');
        $dummyAgent->setPassword(sha1('123456'));
        $dummyAgent->setJobTitle('Support Operator');
        $dummyAgent->setStatus(Agent::STATUS_OFFLINE);
        $dummyAgent->setActive(0);
        $dummyAgent->setAcceptChats(true);
        $dummyAgent->setType(Agent::TYPE_OWNER);
        $dummyAgent->setWidget($this->getReference('widget_regidium_my'));
        $dummyAgent->setRenderVisitorsPeriod(Agent::RENDER_VISITORS_PERIOD_SESSION);
        $manager->persist($dummyAgent);
        $manager->flush($dummyAgent);

        $this->addReference('dummy_agent', $dummyAgent);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
}