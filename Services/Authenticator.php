<?php
/**
 * Created by PhpStorm.
 * User: mmardini
 * Date: 06/11/16
 * Time: 02:13 م
 */

namespace Notification\InstantNotificationBundle\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use LogicException;

class Authenticator
{

    private $client;

    private $clientEnabled;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setClientEnabled($clientEnabled)
    {
        $this->clientEnabled=$clientEnabled;
    }

    public function login($userId)
    {
        if ($this->clientEnabled)
        {
            try
            {
                $response = $this->client->post('/login', ['form_params' => ['userId' => $userId, 'qname' => $userId]]);
                return \GuzzleHttp\json_decode($response->getBody(), true);
            } catch (TransferException $e) {
                //catches all 4xx and 5xx status codes
                //$response = \GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
                //return \GuzzleHttp\json_decode($response->getBody(), true);
                //TODO log error
            }
        }
        return [];
    }
} 