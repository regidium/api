<?php

namespace Regidium\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConverDateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('regidium:convert:date')
            ->setDescription('Convert date')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $chats = $dm->getRepository('Regidium\CommonBundle\Document\Chat')->findAll();
        foreach($chats as $chat) {
            $old = $chat->getEndedAt();
            $chat->setEndedAt($old->getTimestamp());
            $dm->persist($chat);
        }
        $dm->flush();

        return;
    }
}