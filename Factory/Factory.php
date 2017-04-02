<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 01:10 م
 */
namespace Notification\InstantNotificationBundle\Factory;


use Doctrine\ORM\EntityManager;
use Notification\InstantNotificationBundle\Entity\Notification;
use Notification\InstantNotificationBundle\Formatter\JSONFormatter;
use Notification\InstantNotificationBundle\Formatter\PlainTextFormatter;
use Notification\InstantNotificationBundle\Handler\RSMQHandler;
use Notification\InstantNotificationBundle\Services\Constants;
use Redis\RSMQRESTClientBundle\RestfulClient\MessageAPI;
use Redis\RSMQRESTClientBundle\RestfulClient\QueueAPI;
use Symfony\Component\Form\Exception\LogicException;

class Factory
{

    private $queueAPI;

    private $messageAPI;

    private $em;

    public function __construct(QueueAPI $queueAPI,MessageAPI $messageAPI,EntityManager $em)
    {
        $this->messageAPI=$messageAPI;
        $this->queueAPI=$queueAPI;
        $this->em=$em;
    }

    public function create($sender, $receiver, $message,$img,$url, $type, $status,$application, $format, $doPersist, $handlerType)
    {

        switch ($format) {
            case Constants::PLAIN_TEXT_FORMAT:
                $formatter = new PlainTextFormatter();
                break;
            case Constants::JSON_FORMAT:
                $formatter = new JSONFormatter();
                break;
            default:
                throw new LogicException(sprintf('Message format: (%s) is not supported', $format));
        }

        switch ($handlerType) {
            case Constants::RSMQ_HANDLER:
                $handler = new RSMQHandler($this->queueAPI,$this->messageAPI,$this->em);
                break;
            default:
                throw new LogicException(sprintf('Message handler: (%s) is not supported', $handlerType));
        }
        $notification = new Notification();
        $notification->setIsStorable($doPersist);
        $notification->setMessage($message);
        $notification->setPicturePath($img);
        $notification->setReceiver($receiver);
        $notification->setSender($sender);
        $notification->setFormatter($formatter);
        $notification->setHandler($handler);
        $notification->setType($type);
        $notification->setStatus($status);
        $notification->setApplication($application);
        $notification->setUrl($url);

        return $notification;
    }
} 