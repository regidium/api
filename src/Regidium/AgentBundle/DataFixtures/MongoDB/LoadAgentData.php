<?php

namespace Regidium\AgentBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Agent;

class LoadAgentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dummyAgent = new Agent();
        $dummyAgent->setJobTitle('Dummy Agent');
        $dummyAgent->setStatus(Agent::STATUS_DEFAULT);
        $dummyAgent->setAcceptChats(true);
        $manager->persist($dummyAgent);

        $dummyPerson = new Person();
        $dummyPerson->setAgent($dummyAgent);
        $dummyPerson->setAvatar('/img/employee-photo.jpg');
        $dummyPerson->setCountry('Russia');
        $dummyPerson->setCity('Moscow');
        $dummyPerson->setEmail('dummy.agent@email.com');
        $dummyPerson->setFullname('Dummy Agent');
        $dummyPerson->setPassword(sha1('123456'));
        $dummyPerson->setStatus(Person::STATUS_DEFAULT);
        $manager->persist($dummyPerson);

        $manager->flush();

        $this->addReference('dummyAgent', $dummyAgent);
        $this->addReference('dummyAgentPerson', $dummyPerson);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}