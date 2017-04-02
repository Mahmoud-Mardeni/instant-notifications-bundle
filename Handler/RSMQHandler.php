<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 02:03 م
 */

namespace Notification\InstantNotificationBundle\Handler;


use Doctrine\ORM\EntityManager;
use Notification\InstantNotificationBundle\Entity\Notification;
use Redis\RSMQRESTClientBundle\RestfulClient\MessageAPI;
use Redis\RSMQRESTClientBundle\RestfulClient\QueueAPI;

class RSMQHandler extends GenericHandler{

    private $queueAPI;

    private $messageAPI;

    public function __construct(QueueAPI $queueAPI,MessageAPI $messageAPI,EntityManager $em)
    {
        $this->messageAPI=$messageAPI;
        $this->queueAPI=$queueAPI;
        $this->em=$em;
    }

    public function send(Notification $notification)
    {

        parent::send($notification);
        $queueName=$notification->getQueueName();

        if(!$this->queueExist($queueName))
        {
            $this->queueAPI->create($queueName);
        }

        $response=$this->messageAPI->send($queueName,$this->getMessage($notification));

        $notification->setMessageId($response["id"]);
        $this->em->flush();
    }

    public function queueExist($queueName)
    {
       return in_array($queueName,$this->queueAPI->listQueues());
    }
}