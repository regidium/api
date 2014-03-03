<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\ChatBundle\Form\ChatMessageForm;
use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;
use Regidium\CommonBundle\Document\Person;

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

        return $this->processForm($entity, $data, 'POST');
    }

    /**
     * Обработка формы.
     *
     * @param ChatMessage  $chat_message
     * @param array        $data
     * @param string       $method
     *
     * @return array|ChatMessage
     *
     */
    public function processForm(ChatMessage $chat_message, array $data, $method = 'PUT')
    {
        $form_chat_message = $this->formFactory->create(new ChatMessageForm(), $chat_message, ['method' => $method]);
        $form_chat_message->submit($data, 'PATCH' !== $method);
        if ($form_chat_message->isValid()) {
            /** @var ChatMessage $chat_message */
            $chat_message = $form_chat_message->getData();
            if (!$chat_message instanceof ChatMessage) {
                return 'Server error';
            }

            $chat = $this->dm->getRepository('Regidium\CommonBundle\Document\chat')->findOneBy(['uid' => $form_chat_message->get('chat_uid')->getData()]);

            // Архивируем сообщения, по критерию "все до последних X"
            $chat_messages = $chat->getMessages();
            /** @todo Поменять количество сообщений для архивации, после проверки */
            if (count($chat_messages) > 5) {
                $message_to_archive = $chat->getMessages()->filter(function($e) { return !$e->getArchived(); })->first();
                //$messages_to_archive = $chat->getMessages()->slice(count($chat_messages) - 1, 1);
                if($message_to_archive) {
                    $message_to_archive->setArchived(true);
                    $this->dm->persist($message_to_archive);
                }
            }
            unset($chat_messages);

            $sender = $this->dm->getRepository('Regidium\CommonBundle\Document\person')->findOneBy(['uid' => $form_chat_message->get('sender_uid')->getData()]);
            $chat_message->setSender($sender);

            $chat->addMessage($chat_message);

            $this->dm->persist($chat);

            $this->dm->flush();

            return $chat_message;
        }

        return $this->getFormErrors($form_chat_message);
    }
}