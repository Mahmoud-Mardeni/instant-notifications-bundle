<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 01/11/16
 * Time: 02:04 م
 */

namespace Notification\InstantNotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JsonSerializable;
use Notification\InstantNotificationBundle\Formatter\FormatterInterface;

/**
 * Notification
 *
 * @ORM\Table(name="NOTIFICATION")
 * @ORM\Entity
 */
class Notification implements  JsonSerializable{

    use TimestampableEntity;
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="MESSAGE", type="string",length=2048, nullable=false)
     */
    private $message;
    /**
     * @var string
     *
     * @ORM\Column(name="PICTURE_PATH", type="string",length=1024, nullable=false)
     */
    private $picturePath;

    /**
     * @var string
     *
     * @ORM\Column(name="URL", type="string",length=1024, nullable=true)
     */
    private $url;


    /**
     * @var HrEmployees
     *
     * @ORM\ManyToOne(targetEntity="HR\Bundles\EmployeesBundle\Entity\HrEmployees")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SENDER_ID", referencedColumnName="ID")
     * })
     */
    protected $sender;

    /**
     * @var HrEmployees
     *
     * @ORM\ManyToOne(targetEntity="HR\Bundles\EmployeesBundle\Entity\HrEmployees",inversedBy="notifications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RECEIVER_ID", referencedColumnName="ID")
     * })
     */
    protected $receiver;
    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string",length=256, nullable=false)
     */
    private $type;
    /**
     * @var integer
     *
     * @ORM\Column(name="STATUS", type="integer", nullable=false)
     */
    private $status;
    /**
     * @var string
     *
     * @ORM\Column(name="APPLICATION", type="string",length=256, nullable=false)
     */
    private $application;
    /**
     * @var string
     *
     * @ORM\Column(name="MESSAGE_ID", type="string",length=256, nullable=true)
     */
    private $messageId;

    private $formatter;

    private $isStorable;

    private $handler;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getIsStorable()
    {
        return $this->isStorable;
    }

    /**
     * @param mixed $isStorable
     */
    public function setIsStorable($isStorable)
    {
        $this->isStorable = $isStorable;
    }


    /**
     * @return mixed
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @param mixed $formatter
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format()
    {
       return $this->formatter->format($this);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param mixed $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    public function send()
    {
        $this->handler->send($this);
    }

    /**
     * @return HrEmployees
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param HrEmployees $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return HrEmployees
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param HrEmployees $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param string $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function getQueueName()
    {
        return $this->getReceiver()->getId();
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return string
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @param string $picturePath
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;
    }

    public function jsonSerialize()
    {
       return [
           'receiverId'=>$this->getReceiver()->getId(),
           'message'=>$this->getMessage(),
           'createdAt'=>$this->getCreatedAt()->format('Y-m-d H:i:s'),
           'id'=>$this->id,
           'picturePath'=>$this->getPicturePath(),
           'status'=>$this->getStatus(),
           'url'=>$this->getUrl(),
           'senderId'=>$this->getSender()->getId(),
       ];
    }
}