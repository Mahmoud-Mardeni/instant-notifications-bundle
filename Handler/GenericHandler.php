<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 02/11/16
 * Time: 09:41 ص
 */

namespace Notification\InstantNotificationBundle\Handler;


use Doctrine\ORM\EntityManager;
use Notification\InstantNotificationBundle\Entity\Notification;

class GenericHandler implements HandlerInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }

    public function send(Notification $notification)
    {
        if($notification->getIsStorable())
        {
            $this->em->persist($notification);
            $this->em->flush();
        }
    }

    public function getMessage(Notification $notification)
    {
        return $notification->format();
    }
}