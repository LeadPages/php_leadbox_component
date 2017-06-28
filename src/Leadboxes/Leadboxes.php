<?php

namespace Leadpages\Leadboxes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Leadpages\Auth\LeadpagesLogin;

class Leadboxes
{

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var \Leadpages\Auth\LeadpagesLogin
     */
    private $login;
    /**
     * @var \Leadpages\Auth\LeadpagesLogin
     */
    public $response;

    /**
     * @property string leadboxesUrl
     */
    public $leadboxesUrl;
    public $certFile;

   /**
    * @todo Add configuration/DI option for leadboxesUrl for testability
    * @todo Make certFile path configurable and optional
    *
    * @param \GuzzleHttp\Client $client
    * @param LeadpagesLogin     $login 
    *
    */
    public function __construct(Client $client, LeadpagesLogin $login)
    {
        $this->client = $client;
        $this->login = $login;
        $this->login->getApiKey();
        $this->leadboxesUrl = "https://api.leadpages.io/content/v1/leadboxes";
        $this->certFile = ABSPATH . WPINC . '/certificates/ca-bundle.crt';
    }


    /**
     * @return mixed|Exception
     */
    public function getAllLeadboxes()
    {
        try {
            $response = $this->client->get($this->leadboxesUrl, [
                'headers' => ['Authorization' => 'Bearer ' . $this->login->apiKey],
                'verify' => $this->certFile,
            ]);

            $response = [
                'code' => '200',
                'response' => $response->getBody()->getContents()
            ];

        } catch (ClientException $e) {
            $response = $this->parseException($e);

        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);
        }

        return $response;
    }

   /**
    * Fetch leadbox embed code by id + type
    *
    * @param string $id
    * @param string $type
    * @param string $content
    *
    * @return mixed|Exception
    */
    public function getSingleLeadboxEmbedCode($id, $type = '', $content = '')
    {
        try {
            $url = $this->buildSingleLeadboxUrl($id, $type);
            $response = $this->client->get($url, [
                'headers' => ['Authorization' => 'Bearer '. $this->login->apiKey],
                'verify' => $this->certFile,
            ]);

            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);
            $embed_code = $body['_items']['publish_settings']['embed_code'];

            $response = [
                'code' => '200',
                'response' => json_encode(['embed_code' => $embed_code])
            ];

        } catch (ClientException $e) {
            $response = $this->parseException($e);

        } catch (ServerException $e) {
            $response = $this->parseException($e);

        } catch (ConnectException $e) {
            $message = 'Can not connect to Leadpages Server:';
            $response = $this->parseException($e, $message);
        }

        return $response;
    }

   /**
    * 
    * @param string $id
    * @param string $type
    *        
    * @return string Leadbox url
    */
    public function buildSingleLeadboxUrl($id, $type)
    {
        $queryParams = http_build_query(['popup_type' => $type]);
        return $this->leadboxesUrl . '/' . $id . '?' . $queryParams;
    }


    /**
     * Build response data structure for errors
     *
     * @param Exception $e
     * @param string    $message
     *
     * @return array
     */
    public function parseException($e, $message = '')
    {
        return [
            'code' => $e->getCode(),
            'response' => $message . ' ' . $e->getMessage(),
            'error' => true
        ];
    }

}

