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
     * @param array        $parameters
     * @param string       $method
     *
     * @return array|ChatMessage
     *
     */
    public function processForm(ChatMessage $chat_message, array $parameters, $method = 'PUT')
    {
        $form_chat_message = $this->formFactory->create(new ChatMessageForm(), $chat_message, ['method' => $method]);
        $form_chat_message->submit($parameters, 'PATCH' !== $method);
        if ($form_chat_message->isValid()) {
            /** @var ChatMessage $chat_message */
            $chat_message = $form_chat_message->getData();
            if (!$chat_message instanceof ChatMessage) {
                return 'Server error';
            }

            $chat = $this->dm->getRepository('Regidium\CommonBundle\Document\chat')->findOneBy(['uid' => $form_chat_message->get('chat_uid')->getData()]);
            $chat_message->setChat($chat);

            $sender = $this->dm->getRepository('Regidium\CommonBundle\Document\person')->findOneBy(['uid' => $form_chat_message->get('sender_uid')->getData()]);
            $chat_message->setSender($sender);

            $receiver = $this->dm->getRepository('Regidium\CommonBundle\Document\person')->findOneBy(['uid' => $form_chat_message->get('receiver_uid')->getData()]);
            if ($receiver instanceof Person) {
                $chat_message->setReceiver($receiver);
            }

            $this->dm->persist($chat_message);
            $this->dm->flush($chat_message);

            return $chat_message;
        }

        return $this->getFormErrors($form_chat_message);
    }
}