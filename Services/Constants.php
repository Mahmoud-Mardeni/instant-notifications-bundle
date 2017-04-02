<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 01:13 م
 */
namespace Notification\InstantNotificationBundle\Services;

class Constants {

    const JSON_FORMAT = 1;
    const PLAIN_TEXT_FORMAT = 2;

    const RSMQ_HANDLER=1;

    const MESSAGE_FORMATS=[
        1=>['formatter'=>''],
        2=>['formatter'=>''],
    ];

    const MESSAGE_HANDLERS=[
        1=>['class'=>''],
    ];

} 