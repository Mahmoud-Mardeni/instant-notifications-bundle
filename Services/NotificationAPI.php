<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 01:52 م
 */


namespace Notification\InstantNotificationBundle\Services;


use Doctrine\ORM\EntityManager;
use Notification\InstantNotificationBundle\Factory\Factory;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class NotificationAPI
{

    private $factory;

    private $em;

    private $notificationStatusList;

    private $serverEnabled;

    public function __construct(Factory $factory, EntityManager $em, $notificationStatusList)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->notificationStatusList = $notificationStatusList;
    }

    public function setServerEnabled($serverEnabled)
    {
        $this->serverEnabled=$serverEnabled;
    }

    public function send($senderId, $receiverId, $message, $img,$url, $type, $status, $application, $format, $doPersist, $handlerType)
    {
        if($this->serverEnabled) {

            try {
                $notification = $this->factory->create($senderId, $receiverId, $message, $img, $url, $type, $status, $application, $format, $doPersist, $handlerType);
                $notification->send();
            }catch (LogicException $e){

            }
        }
    }

    public function setAllSeen($receiver)
    {
        if($this->serverEnabled) {
            $notifications = $this->em->getRepository('InstantNotificationBundle:Notification')->findBy(['receiver' => $receiver, 'status' => $this->notificationStatusList['not_seen']]);
            foreach ($notifications as $notification) {
                $notification->setStatus($this->notificationStatusList['seen']);
            }
            $this->em->flush();
        }
    }

    public function lazyLoad($lastItemId, $limit, $receiver)
    {
        if($this->serverEnabled) {
            $this->setAllSeen($receiver);

            $qb = $this->em->getRepository("InstantNotificationBundle:Notification")->createQueryBuilder('notification')
                ->select("notification")
                ->where('notification.receiver=:current_user')
                ->orderBy('notification.createdAt', 'desc')
                ->addOrderBy('notification.id', 'desc')
                ->setMaxResults($limit)
                ->setParameter('current_user', $receiver);

            if ($lastItemId != null) {
                $qb->andWhere('notification.id < :lastNotificationId')
                    ->setParameter('lastNotificationId', $lastItemId);
            }
            return $qb->getQuery()->getArrayResult();
        }
        return [];
    }

    public function setRead($notificationId,$receiver)
    {
        if($this->serverEnabled) {
            $seenStatus = $this->notificationStatusList['seen'];
            switch ($notificationId) {
                case -1:
                    $unreadNotifications = $this->em->getRepository('InstantNotificationBundle:Notification')->findBy(['receiver' => $receiver, 'status' => $seenStatus]);
                    foreach ($unreadNotifications as $notification) {
                        $notification->setStatus($this->notificationStatusList['seen_and_read']);
                        $this->em->flush();
                    }
                    return true;
                default:
                    $notification = $this->em->getRepository('InstantNotificationBundle:Notification')->findOneBy(['receiver' => $receiver, 'id' => $notificationId, 'status' => $seenStatus]);
                    if ($notification) {
                        $notification->setStatus($this->notificationStatusList['seen_and_read']);
                        $this->em->flush();
                        return true;
                    }
            }
        }
        return false;
    }


} 