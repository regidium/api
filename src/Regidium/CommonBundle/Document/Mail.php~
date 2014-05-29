<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\MailRepository",
 *      collection="mails",
 *      requireIndexes=false
 *  )
 */
class Mail
{
    /* =============== Attributes =============== */

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $uid;

    /**
     * @Assert\NotBlank
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $created_at;

    /**
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $sended_at;

    /**
     * @Assert\NotBlank
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $status;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $title;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $template;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $sender_email;

    /**
     * @MongoDB\Collection
     */
    private $receiver_emails;

    /**
     * @MongoDB\Hash
     */
    private $data;

    /* =============== Constants =============== */

    const STATUS_NOT_SENDED   = 1;
    const STATUS_SENDED = 2;

    static public function getStatuses()
    {
        return [
            self::STATUS_NOT_SENDED,
            self::STATUS_SENDED
        ];
    }

    /* ============= COMMON ============= */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setCreatedAt(time());
        $this->setStatus(self::STATUS_NOT_SENDED);
        $this->setReceiverEmails([]);
        $this->setData([]);
    }

    public function toArray(array $options = [])
    {
        $return = [
            'uid' => $this->getUid(),
            'status' => $this->getStatus(),
            'created_at' => $this->getCratedAt(),
            'sended_at' => $this->getCratedAt(),
            'title' => $this->getTitle(),
            'template' => $this->getTemplate(),
            'sender_email' => $this->getSenderEmail(),
            'receiver_emails' => $this->getReceiverEmails(),
            'data' => $this->getData(),
        ];

        return $return;
    }

    /* ============= GET/SET ============= */

    /**
     * Set id
     *
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return self
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Get uid
     *
     * @return string $uid
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set createdAt
     *
     * @param int $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return int $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set sendedAt
     *
     * @param int $sendedAt
     * @return self
     */
    public function setSendedAt($sendedAt)
    {
        $this->sended_at = $sendedAt;
        return $this;
    }

    /**
     * Get sendedAt
     *
     * @return int $sendedAt
     */
    public function getSendedAt()
    {
        return $this->sended_at;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return string $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set senderEmail
     *
     * @param string $senderEmail
     * @return self
     */
    public function setSenderEmail($senderEmail)
    {
        $this->sender_email = $senderEmail;
        return $this;
    }

    /**
     * Get senderEmail
     *
     * @return string $senderEmail
     */
    public function getSenderEmail()
    {
        return $this->sender_email;
    }

    /**
     * Set data
     *
     * @param hash $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get data
     *
     * @return hash $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set receiverEmails
     *
     * @param array $receiverEmails
     * @return self
     */
    public function setReceiverEmails($receiverEmails)
    {
        $this->receiver_emails = $receiverEmails;
        return $this;
    }

    /**
     * Get receiverEmails
     *
     * @return array $receiverEmails
     */
    public function getReceiverEmails()
    {
        return $this->receiver_emails;
    }
}
