<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 02:13 م
 */

namespace Notification\InstantNotificationBundle\Formatter;


use Notification\InstantNotificationBundle\Entity\Notification;

class PlainTextFormatter implements FormatterInterface {

    public function format(Notification $notification)
    {
      return $notification->getMessage();
    }
}