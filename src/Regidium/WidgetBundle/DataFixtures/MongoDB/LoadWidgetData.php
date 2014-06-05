<?php

namespace Regidium\WidgetBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Regidium\CommonBundle\Document\Widget;

class LoadWidgetData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $widget = new Widget();
        $widget->setUid('5301e0397eaae');
        $widget->setUrl('http://my.regidium.com');
        $widget->setBalance(0);
        $widget->setAvailableAgents(0);
        $widget->setStatus(Widget::STATUS_DEFAULT);
        $widget->setPlan($this->getReference('plan_base'));

        $manager->persist($widget);
        $manager->flush($widget);

        $this->addReference('widget_regidium_my', $widget);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}