<?php

namespace Regidium\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateIndexesMongoDbCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('db:create:indexes')
            ->setDescription('Create indexes for MongoDB base')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('doctrine.odm.mongodb.document_manager')->getSchemaManager()->ensureIndexes();

        $this->writeMessage('Indexes created');

        return;
    }
}