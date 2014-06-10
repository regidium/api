<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;

use Regidium\ChatBundle\Form\ChatForm;
use Regidium\ChatBundle\Form\UserForm;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;
use Regidium\CommonBundle\Document\Widget;

class ChatHandler extends AbstractHandler
{
    /**
     * @todo
     * Создание новой сущности
     *
     * @param array $data
     *
     * @return object
     */
    public function post(array $data)
    {
        $entity = $this->createEntity();

        $user = new User();
        $user_form = $this->form_factory->create(new UserForm(), $user, ['method' => 'POST']);
        $user_form->submit($data['user'], false);
        $user = $user_form->getData();
        $data['user'] = $user;

        /** @todo не присылать */
        unset($data['messages']);

        $form = $this->form_factory->create(new ChatForm(), $entity, ['method' => 'POST']);
        $form->submit($data, false);

        if ($form->isValid()) {
            /** @var Chat $chat */
            $chat = $form->getData();
            if (!$chat instanceof Chat) {
                return 'Server error';
            }

            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $form->get('widget_uid')->getData()]);
            $notifications = $widget->getNotifications();
            if (!$notifications['install_code']['show']) {
                $notifications['install_code']['show'] = true;
                $widget->setNotifications($notifications);
                $this->dm->persist($widget);
                $this->dm->flush($widget);
            }
            $chat->setWidget($widget);

            $this->dm->persist($chat);
            $this->dm->flush($chat);

            return $chat;
        }

        return $this->getFormErrors($form);
    }

    /**
     * Подключение чата
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function online(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_ONLINE);
        $chat->setEndedAt(null);
        $this->edit($chat);

        return $chat;
    }

    /**
     * Общение в чате
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function chatting(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_CHATTING);
        $chat->setOpened(true);
        $chat->setEndedAt(null);
        $this->edit($chat);

        return $chat;
    }

    /**
     * Отключение чата
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function offline(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_OFFLINE);
        $chat->setEndedAt(time());
        $this->edit($chat);

        return $chat;
    }

    /**
     * Закрытие чата
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function closed(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_ONLINE);
        $chat->setClosed();
        //$this->edit($chat);
        $this->dm->persist($chat);
        $this->dm->flush($chat);

        return $chat;
    }

    /**
     * Авторизационные данные пользователя
     *
     * @param Chat $chat
     * @param array $data
     *
     * @return Chat
     */
    public function auth(Chat $chat, $data) {
        $user = $chat->getUser();
        $user->setFirstName($data['first_name']);
        $user->setEmail($data['email']);

        $chat->setUser($user);
        $this->edit($chat);

        return $chat;
    }

    /**
     * Агент подключился
     *
     * @param Chat $chat
     * @param Agent $agent
     *
     * @return Chat
     */
    public function agentEnter(Chat $chat, Agent $agent) {
        $chat->setAgent($agent);
        if ($chat->getStatus() != Chat::STATUS_OFFLINE) {
            $chat->setStatus(Chat::STATUS_CHATTING);
        }

        // Прочитываем все сообщения чата
        /** @var ChatMessage[] $messages  */
        $messages = $chat->getMessages();
        foreach($messages as $message) {
            $message->setReaded(true);
            $this->dm->persist($message);
        }

        $this->dm->persist($chat);
        $this->dm->flush();

        return $chat;
    }

    /**
     * Агент отключился
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function agentLeave(Chat $chat) {
        $chat->clearAgent();
        //$chat->setStatus(Chat::STATUS_ONLINE);

        $this->edit($chat);

        return $chat;
    }

    /**
     * Смена URL чата
     *
     * @param Chat   $chat
     * @param string $current_url
     *
     * @return Chat
     */
    public function changeUrl(Chat $chat, $current_url ='') {
        $chat->setCurrentUrl($current_url);
        return $this->edit($chat);
    }

    /**
     * Смена Referrer сайта
     *
     * @param Chat   $chat
     * @param string $referrer
     *
     * @return Chat
     */
    public function changeReferrer(Chat $chat, $referrer = '') {
        $parsed_url = parse_url(urldecode($referrer));
        if (!isset($parsed_url['query'])) {
            $parsed_url['query'] = '';
        }
        if (!isset($parsed_url['fragment'])) {
            $parsed_url['fragment'] = '';
        }
        parse_str($parsed_url['query'], $parsed_query);

        if (!$parsed_query['q']) {
            parse_str($parsed_url['fragment'], $parsed_query);
        }

        if ($parsed_url) {
            if (strpos($parsed_url['host'],'test.') !== false ||
                strpos($parsed_url['host'],'google.') !== false ||
                strpos($parsed_url['host'],'bing.') !== false ||
                strpos($parsed_url['host'],'mail.') !== false) {
                $chat->setReferrer(urlencode($parsed_url['scheme'].'://'.$parsed_url['host']));

                if (isset($parsed_query['q'])) {
                    $chat->setKeywords($parsed_query['q']);
                }
            } elseif (strpos($parsed_url['host'],'yandex.') !== false) {
                $chat->setReferrer(urlencode($parsed_url['scheme'].'://'.$parsed_url['host']));

                if (isset($parsed_query['text'])) {
                    $chat->setKeywords($parsed_query['text']);
                }
            } elseif (strpos($parsed_url['host'],'rambler.') !== false) {
                $chat->setReferrer(urlencode($parsed_url['scheme'].'://'.$parsed_url['host']));

                if (isset($parsed_query['query'])) {
                    $chat->setKeywords($parsed_query['query']);
                }
            } else {
                $chat->setReferrer($referrer);
            }
            $this->edit($chat);
        }

        return $chat;
    }

    /**
     * @todo убрать
     *
     * Save edited Chat
     *
     * @param Chat  $chat
     *
     * @return Chat
     */
    public function edit(Chat $chat) {
        $this->dm->persist($chat);
        $this->dm->flush($chat);

        return $chat;
    }

    /**
     * Редактирование чата
     *
     * @param Chat  $chat
     * @param array $data
     *
     * @return Chat
     */
    public function patch(Chat $chat, array $data = []) {
        $chat = $this->processForm($chat, $data, 'PATCH');

        return $chat;
    }

    /**
     * Обработка формы.
     *
     * @param Chat  $chat
     * @param array  $data
     * @param string $method
     *
     * @return string|array|Chat
     *
     */
    public function processForm(Chat $chat, array $data, $method = 'PUT')
    {
        $form = $this->form_factory->create(new ChatForm(), $chat, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            /** @var Chat $chat */
            $chat = $form->getData();
            if (!$chat instanceof Chat) {
                return 'Server error';
            }

            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $form->get('widget_uid')->getData()]);
            $chat->setWidget($widget);

            $this->dm->persist($chat);
            $this->dm->flush($chat);

            return $chat;
        }

        return $this->getFormErrors($form);
    }
}