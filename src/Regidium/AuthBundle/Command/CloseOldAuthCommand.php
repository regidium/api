<?php

namespace Regidium\AuthBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @todo Update */
class CloseOldAuthCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('regidium:auth:close_old_session')
            ->setDescription('Close old session')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '6400M');

        $output->writeLn('Start close old session');

        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $session_max_age = $this->getContainer()->getParameter('session')['max_age'];

        $qb = $dm->createQueryBuilder('Regidium\CommonBundle\Document\Auth')
            ->field('remember')->equals(false)
            ->field('ended')->exists(false)
            ->field('started.sec')->lte(time() - $session_max_age);
        $auths = $qb->getQuery()->execute();

        $output->writeLn('Finded '.$auths->count().' old sessions.');

        foreach ($auths as $auth) {
            $auth->setEnded(time());
        }

        $dm->flush();

        $output->writeLn('All old session is closed');

    }

}