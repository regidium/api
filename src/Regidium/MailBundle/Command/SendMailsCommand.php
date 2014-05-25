<?php

namespace Regidium\MailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Regidium\CommonBundle\Document\Mail;

class SendMailsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('regidium:mails:send')
            ->setDescription('Send email pool')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $templating = $this->getContainer()->get('templating');
        $mailer = $this->getContainer()->get('mailer');

        $mails = $dm->getRepository('Regidium\CommonBundle\Document\Mail')->findBy(['status' => Mail::STATUS_NOT_SENDED], ['created_at' => 'asc'], 10);
        $output->writeln('Finded '.count($mails).' message in spool');
        /** @var Mail[] $mails */
        foreach($mails as $mail) {
            $message = \Swift_Message::newInstance('/usr/sbin/sendmail')
                ->setSubject($mail->getTitle())
                ->setFrom($mail->getSenderEmail())
                ->setTo($mail->getReceiverEmails())
                ->setFormat('text/html')
                ->setBody(
                    $templating->render(
                        $mail->getTemplate(),
                        $mail->getData()
                    )
                )
            ;

            $status = $mailer->send($message);

            $mail->setStatus(Mail::STATUS_SENDED);
            $mail->setSendedAt(time());
            $dm->persist($mail);

            $output->writeln('Mail '.$mail->getId().' status '.$status);
        }
        $dm->flush();

        return;
    }
}