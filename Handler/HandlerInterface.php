<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 01:35 م
 */

namespace Notification\InstantNotificationBundle\Handler;

use Notification\InstantNotificationBundle\Entity\Notification;

interface HandlerInterface {

    public function send(Notification $notification);
} 