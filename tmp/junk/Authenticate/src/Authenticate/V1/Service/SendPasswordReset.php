<?php
namespace Authenticate\V1\Service;

use IgnUser\Entity\User;
use Zend\Math\Rand;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\StorageInterface;
use Zend\Db\Adapter\Adapter;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\Plugin\Url;


class SendPasswordReset
{
    /** @var Smtp */
    private $smtpAdapter;

    /** @var string */
    private $message;

    /** @var int */
    private $errorCode;

    /** @var  TranslatorInterface */
    private $translator;

    /** @var Filesystem */
    private $cacheAdapter;

    /** @var User */
    private $user;

    /** @var Adapter */
    private $dbAdapter;

    /** @var string */
    private $token;

    /** @var  Url */
    private $urlPlugin;

    function __construct(StorageInterface $cacheAdapter, Smtp $smtpAdapter, Adapter $dbAdapter, TranslatorInterface $translator, Url $urlPlugin)
    {
        $this->cacheAdapter = $cacheAdapter;
        $this->smtpAdapter = $smtpAdapter;
        $this->dbAdapter = $dbAdapter;
        $this->translator = $translator;
        $this->urlPlugin = $urlPlugin;
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function sendTo(User $user)
    {
        $this->user = $user;
        $this->createToken();
        return $this->sendMail();
    }

    /**
     * @return bool
     */
    private function sendMail()
    {
        $m = new Message();
        $m->addTo($this->user->getEmail())->setFrom('info@infogan.co.il', $this->translator->translate("Infogan System"))
            ->setSubject($this->translator->translate("Your request to reset password at Infogan"))
            ->setBody($this->generateMessage());

        try {
            $this->getSmtpAdapter()->send($m);
        } catch (\Exception $ex) {
            $this->errorCode = 500;
            $this->message = $ex->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    private function generateMessage()
    {
        $m = $this->translator->translate("Please click on the link to reset your password") . ".\n";
        $m .= $this->urlPlugin->fromRoute('reset_password_view', ['token' => $this->token], ['force_canonical' => true]);
        return $m;
    }

    /**
     * @return int
     */
    public function getHttpErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->message;
    }

    /**
     * @return Smtp
     */
    private function getSmtpAdapter()
    {
        return $this->smtpAdapter;
    }

    /**
     * @return Filesystem
     */
    private function getCacheAdapter()
    {
        return $this->cacheAdapter;
    }

    private function createToken()
    {
        $this->token = (Rand::getString(32, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', true));
        $this->getCacheAdapter()->addItem($this->token, $this->user->getUsername());
    }

}