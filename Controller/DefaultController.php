<?php

namespace Notification\InstantNotificationBundle\Controller;

use Notification\InstantNotificationBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("has_role('ROLE_USER')")
 */
class DefaultController extends Controller
{


    public function indexAction(Request $request)
    {
        return $this->render('InstantNotificationBundle:Default:index.html.twig', ['notifications' => json_encode($this->getUser()->getNotifications())]);
    }

    public function setReadAction(Request $request)
    {
        $notificationId = $request->request->get('id');
        $result = $this->get('notification.instant_notification.api')->setRead($notificationId, $this->getUser());

        if ($result) {
            return new JsonResponse(['success' => $result]);
        }
        return new JsonResponse(['success' => $result, 'error' => 'notification does not exist.']);
    }


    public function setAllSeenAction(Request $request)
    {
        $this->get('notification.instant_notification.api')->setAllSeen($this->getUser());
        return new JsonResponse(['success' => true]);
    }

    public function fetchAction(Request $request)
    {
        $lastNotificationId = $request->get('lastItemId');
        $limit = $request->get('limit');
        return new JsonResponse(['results' => $this->get('notification.instant_notification.api')->lazyLoad($lastNotificationId, $limit, $this->getUser())]);
    }
}
