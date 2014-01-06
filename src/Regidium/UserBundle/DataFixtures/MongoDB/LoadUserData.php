<?php

namespace Regidium\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
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
        $dummyUser->setFullname('Dummy User');
        $dummyUser->setEmail('dummy.user@email.com');
        $dummyUser->setPassword(sha1('123456'));
        $dummyUser->setState(User::STATE_DEFAULT);

        $manager->persist($dummyUser);
        $manager->flush();

        $this->addReference('dummy-user', $dummyUser);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}