<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\ChatBundle\Form\ChatMessageForm;
use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;

class ChatMessageHandler extends AbstractHandler
{
    /**
     * Создание новой сущности
     *
     * @param array $data
     *
     * @return object
     */
    public function post(array $data)
    {
        $entity = $this->createEntity();

        $form = $this->formFactory->create(new ChatMessageForm(), $entity, ['method' => 'POST']);
        $form->submit($data, false);
        if ($form->isValid()) {
            /** @var ChatMessage $chat_message */
            $chat_message = $form->getData();
            if (!$chat_message instanceof ChatMessage) {
                return 'Server error';
            }

            $chat = $this->dm->getRepository('Regidium\CommonBundle\Document\Chat')->findOneBy(['uid' => $form->get('chat_uid')->getData()]);
            $chat_message->setChat($chat);

            // Архивируем сообщения, по критерию "все до последних X"
            $old_messages = $this->dm->getRepository('Regidium\CommonBundle\Document\ChatMessage')->findBy(['chat.uid' => $form->get('chat_uid')->getData(), 'archived' => false]);
            /** @todo Поменять количество сообщений для архивации, после проверки */
            if (count($old_messages) > 5) {
                $message_to_archive = $old_messages->first();
                if($message_to_archive) {
                    $message_to_archive->setArchived(true);
                    $this->dm->persist($message_to_archive);
                }
            }
            unset($old_messages);

            $this->dm->persist($chat_message);
            $this->dm->flush();

            return $chat_message;
        }

        var_dump($form->getErrors());die();

        return $this->getFormErrors($form);
    }
}