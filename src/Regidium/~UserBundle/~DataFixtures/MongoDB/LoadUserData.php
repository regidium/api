<?php

namespace Regidium\UserBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dummyUser = new User();
        $dummyUser->setStatus(User::STATUS_DEFAULT);
        $dummyUser->setWidget($this->getReference('widget_regidium_my'));
        $manager->persist($dummyUser);

        $dummyPerson = new Person();
        $dummyPerson->setUser($dummyUser);
        $dummyPerson->setAvatar('http://thecontentwrangler.com/wp-content/uploads/2011/08/User-e1314126998577.png');
        $dummyPerson->setCountry('Ukraine');
        $dummyPerson->setCity('Kiev');
        $dummyPerson->setEmail('dummy.user@email.com');
        $dummyPerson->setFullname('Dummy User');
        $dummyPerson->setPassword(sha1('123456'));
        $dummyPerson->setStatus(User::STATUS_DEFAULT);
        $manager->persist($dummyPerson);

        $manager->flush();

        $this->addReference('dummy_user', $dummyUser);
        $this->addReference('dummy_user_person', $dummyPerson);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }
}