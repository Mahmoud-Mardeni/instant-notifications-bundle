<?php

namespace Notification\InstantNotificationBundle\Traits;

trait NotifiableEntity {

    /**
     * @var string
     *
     * @ORM\Column(name="JSON_WEB_TOKEN", type="string", length=1024, nullable=true)
     */
    private $jsonWebToken;

    /**
     * @return string
     */
    public function getJsonWebToken()
    {
        return $this->jsonWebToken;
    }

    /**
     * @param string $jsonWebToken
     */
    public function setJsonWebToken($jsonWebToken)
    {
        $this->jsonWebToken = $jsonWebToken;
    }

    public function getNotificationsByStatus($status)
    {
        $result = [];
        foreach ($this->notifications as $notification) {
            if ($notification->getStatus() == $status) {
                $result[] = $notification;
            }
        }
        return $result;
    }

    /**
     * @ORM\OneToMany(targetEntity="Notification\InstantNotificationBundle\Entity\Notification", mappedBy="receiver")
     * @ORM\OrderBy({"createdAt" = "DESC", "id" = "DESC"})
     */
    private $notifications;

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications->toArray();
    }

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }
} 