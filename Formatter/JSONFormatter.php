<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 02:13 م
 */

namespace Notification\InstantNotificationBundle\Formatter;


use Notification\InstantNotificationBundle\Entity\Notification;

class JSONFormatter implements FormatterInterface {

    public function format(Notification $notification)
    {
       return json_encode($notification);
    }
}