<?php

namespace Regidium\ClientBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\ClientBundle\Document\Client;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setUrl('http://my.regidium.loc');
        $client->setBalance(0);
        $client->setStatus(Client::STATUS_DEFAULT);
        $client->setPlan($this->getReference('plan_expanded'));

        $manager->persist($client);
        $manager->flush();

        $this->addReference('client_regidium', $client);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6;
    }
}