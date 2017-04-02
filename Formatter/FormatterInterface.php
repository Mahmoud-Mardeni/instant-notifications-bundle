<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 02:10 م
 */

namespace Notification\InstantNotificationBundle\Formatter;

use Notification\InstantNotificationBundle\Entity\Notification;

interface FormatterInterface {

    public function format(Notification $notification);
} 