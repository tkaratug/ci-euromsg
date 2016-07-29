<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| EuroMessage Service Library
|--------------------------------------------------------------------------
| @author   Turan Karatuğ - www.turankaratug.com
| @date     27.07.2016
| @package  Codeigniter
|--------------------------------------------------------------------------
*/

class Euromsg
{

    // EuroMsg Login Service URL
    private $authUrl;

    // EuroMsg Post Service URL
    private $postUrl;

    // EuroMsg Username
    private $userName;

    // EuroMsg Passwırd
    private $userPass;

    // Mail Charset
    private $charset = 'UTF-8';

    // Service Ticket
    private $serviceTicket;

    // Sender E-Mail Address
    private $fromEmail;

    // Sender Name
    private $fromName;

    // Reply E-Mail Address
    private $replyEmail;

    // Receiver E-Mail Address
    private $toEmail;

    // Receiver Name
    private $toName;

    // Mail Subject
    private $subject;

    // Mail Message
    private $message;

    // Codeigniter instance
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->config('euromsg');

        $this->authUrl      = $this->config->item('authUrl');
        $this->postUrl      = $this->config->item('postUrl');
        $this->userName     = $this->config->item('userName');
        $this->userPass     = $this->config->item('userPass');
    }

    /**
     * Login Service
     * @return mixed
     */
    public function login()
    {
        $EuroMsg              = $this->client($this->authUrl);
        $loginWebService      = $EuroMsg->Login(array("Username" => $this->userName, "Password" => $this->userPass));
        $loginWebService      = $loginWebService->LoginResult;
        $this->serviceTicket  = $loginWebService->ServiceTicket;
        return $this->serviceTicket;
    }

    /**
     * Logout
     * @param $serviceTicket
     * @return mixed
     */
    public function logout()
    {
        $EuroMsg          = $this->client($this->authUrl);
        $getLogOutProcess = $EuroMsg->Logout($this->serviceTicket);
        return $getLogOutProcess;
    }

    /**
     * Create SOAP Client
     * @param $url
     * @return SoapClient
     */
    private function client($url)
    {
        return new SoapClient($url);
    }

    /**
     * Set Charset
     * @param $charset
     */
    public function charset($charset)
    {
        $this->charset      = $charset;
    }

    /**
     * Set E-Mail and Name of Sender
     * @param $email
     * @param null $name
     */
    public function from($email, $name = null)
    {
        $this->fromEmail    = $email;
        $this->fromName     = $name;
    }

    /**
     * Set Reply Address
     * @param $email
     */
    public function reply($email)
    {
        $this->replyEmail   = $email;
    }

    /**
     * Set E-Mail and Name of Receiver
     * @param $email
     * @param null $name
     */
    public function to($email, $name = null)
    {
        $this->toEmail      = $email;
        $this->toName       = $name;
    }

    /**
     * Set Mail Subject
     * @param $subject
     */
    public function subject($subject)
    {
        $this->subject      = $subject;
    }

    /**
     * Set Mail Content
     * @param $message
     */
    public function message($message)
    {
        $this->message      = $message;
    }

    /**
     * Send Mail
     */
    public function send()
    {
        $EuroMsg    = $this->client($this->postUrl);
        $mailParams = array(
            'ServiceTicket'     => $this->serviceTicket,
            'FromName'          => $this->fromName,
            'FromAddress'       => $this->fromEmail,
            'ReplyAddress'      => $this->replyEmail,
            'Subject'           => $this->subject,
            'HtmlBody'          => $this->message,
            'Charset'           => $this->charset,
            'ToName'            => $this->toName,
            'ToEmailAddress'    => $this->toEmail,
            'Attachments'       => null
        );

        $send         = $EuroMsg->PostHtml($mailParams);
        $Code         = $send->PostHtmlResult->Code;
        $Message      = $send->PostHtmlResult->Message;
        $detailedMsg  = $send->PostHtmlResult->DetailedMessage;

        return array(
            'code'              => $Code,
            'message'           => $Message,
            'detailedMessage'   => $detailedMsg
        );
    }

}
