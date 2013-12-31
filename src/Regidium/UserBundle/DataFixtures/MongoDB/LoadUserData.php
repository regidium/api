<?php

namespace Regidium\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\Persistence\ObjectManager;
use Regidium\UserBundle\Document\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dummyUser = new User();
        $dummyUser->setFullname('dummy');

        $manager->persist($dummyUser);
        $manager->flush();

        $this->addReference('dummy-user', $dummyUser);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}