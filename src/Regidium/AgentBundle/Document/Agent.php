<?php

    namespace Regidium\AgentBundle\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
    use Symfony\Component\Validator\Constraints as Assert;

    use Regidium\CommonBundle\Document\Interfaces\IdableInterface;
    use Regidium\CommonBundle\Document\Interfaces\StatebleInteface;

    use Regidium\FileBundle\Document\File;
    use Regidium\AuthBundle\Document\Auth;

    /**
     * @todo Увеличивать кол-во чатов при добавлении чата
     */
    /**
     * @MongoDB\Document(
     *      repositoryClass="Regidium\AgentBundle\Repository\AgentRepository",
     *      collection="agents",
     *      requireIndexes=false
     *  )
     */
    class Agent implements IdableInterface, StatebleInteface
    {
        /**
         * @MongoDB\Id
         */
        protected $id;

        /**
         * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
         */
        protected $uid;

        /**
         * @MongoDB\String
         */
        protected $fullname;

        /**
         * @MongoDB\String
         */
        protected $avatar;

        /**
         * @Assert\NotBlank
         * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
         */
        protected $email;

        /**
         * @MongoDB\String
         */
        protected $password;

        /**
         * @Assert\NotBlank
         * @MongoDB\Int
         */
        protected $type;

        /**
         * @Assert\NotBlank
         * @MongoDB\Int
         */
        protected $state;

        /**
         * @Assert\NotBlank
         * @MongoDB\Boolean
         */
        protected $accept_chats;

        /**
         * @MongoDB\Int
         */
        protected $amount_chats;

        /**
         * @MongoDB\Hash
         */
        protected $external_service;

        /**
         * @MongoDB\ReferenceMany(targetDocument="Regidium\AuthBundle\Document\Auth", mappedBy="user")
         */
        protected $auths;

        const TYPE_OPERATOR       = 1;
        const STATE_ADMINISTRATOR = 2;

        static public function getTypes()
        {
            return array(
                self::TYPE_OPERATOR,
                self::STATE_ADMINISTRATOR,
            );
        }

        const STATE_DEFAULT = 1;
        const STATE_BLOCKED = 2;
        const STATE_DELETED = 3;

        static public function getStates()
        {
            return array(
                self::STATE_DEFAULT,
                self::STATE_BLOCKED,
                self::STATE_DELETED
            );
        }

        public function __construct()
        {
            $this->setUid(uniqid());
            $this->setType(self::TYPE_OPERATOR);
            $this->setState(self::STATE_DEFAULT);
            $this->setAcceptChats(true);
            $this->setAmountChats(0);
            $this->setExternalService([]);
        }

        public function __toString()
        {
            return $this->fullname;
        }

        /**
         * Set id
         *
         * @param int $id
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
         * @return id $id
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set uid
         *
         * @param int $uid
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
         * @return uid $uid
         */
        public function getUid()
        {
            return $this->uid;
        }

        /**
         * Set fullname
         *
         * @param string $fullname
         * @return self
         */
        public function setFullname($fullname)
        {
            $this->fullname = $fullname;
            return $this;
        }

        /**
         * Get fullname
         *
         * @return string $fullname
         */
        public function getFullname()
        {
            return $this->fullname;
        }

        /**
         * Set avatar
         *
         * @param string $avatar
         * @return self
         */
        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
            return $this;
        }

        /**
         * Get avatar
         *
         * @return string $avatar
         */
        public function getAvatar()
        {
            return $this->avatar;
        }

        /**
         * Set email
         *
         * @param string $email
         * @return self
         */
        public function setEmail($email)
        {
            $this->email = strtolower($email);
            return $this;
        }

        /**
         * Get email
         *
         * @return string $email
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set password
         *
         * @param string $password
         * @return self
         */
        public function setPassword($password)
        {
            $this->password = $password;
            return $this;
        }

        /**
         * Get password
         *
         * @return string $password
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * Set type
         *
         * @param string $type
         *
         * @throws \InvalidArgumentException
         * @return self
         */
        public function setType($type)
        {
            if (!in_array($type, self::getTypes())) {
                throw new \InvalidArgumentException("Invalid type: {$type}");
            }
            $this->type = $type;
            return $this;
        }

        /**
         * Get type
         *
         * @return int $type
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * Set state
         *
         * @param string $state
         *
         * @throws \InvalidArgumentException
         * @return self
         */
        public function setState($state)
        {
            if (!in_array($state, self::getStates())) {
                throw new \InvalidArgumentException("Invalid state: {$state}");
            }
            $this->state = $state;
            return $this;
        }

        /**
         * Get state
         *
         * @return int $state
         */
        public function getState()
        {
            return $this->state;
        }

        /**
         * Set accept_chats
         *
         * @param bool $accept_chats
         * @return self
         */
        public function setAcceptChats($accept_chats)
        {
            $this->accept_chats = $accept_chats;
            return $this;
        }

        /**
         * Get accept_chats
         *
         * @return bool $accept_chats
         */
        public function getAcceptChats()
        {
            return $this->accept_chats;
        }

        /**
         * Set amount_chats
         *
         * @param int $amount_chats
         * @return self
         */
        public function setAmountChats($amount_chats)
        {
            $this->amount_chats = $amount_chats;
            return $this;
        }

        /**
         * Get amount_chats
         *
         * @return int $amount_chats
         */
        public function getAmountChats()
        {
            return $this->amount_chats;
        }

        /**
         * Set external_service
         *
         * @param array $external_service
         * @return self
         */
        public function setExternalService($external_service)
        {
            $this->external_service = $external_service;
            return $this;
        }

        /**
         * Get external_service
         *
         * @return array $external_service
         */
        public function getExternalService()
        {
            return $this->external_service;
        }

        /**
         * Add auth
         *
         * @param Auth $auth
         */
        public function addAuth(Auth $auth)
        {
            $this->auths[] = $auth;
        }

        /**
         * Remove auth
         *
         * @param Auth $auth
         */
        public function removeAuth(Auth $auth)
        {
            $this->auths->removeElement($auth);
        }

        /**
         * Get auths
         *
         * @return \Doctrine\Common\Collections\Collection $auths
         */
        public function getAuths()
        {
            return $this->auths;
        }
    }
