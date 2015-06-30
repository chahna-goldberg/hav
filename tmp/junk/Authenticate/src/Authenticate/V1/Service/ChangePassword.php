<?php
namespace Authenticate\V1\Service;

use Application\Service\MapperWatcher;
use IgnUser\Entity\User;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Math\Rand;

class ChangePassword
{

    /** @var String */
    private $newPassword;

    /** @var User */
    private $user;

    /** @var Adapter */
    private $dbAdapter;

    /** @var int */
    private $passOutdated;

    /** @var string */
    private $message;

    /** @var int */
    private $errorCode;

    /** @var  TranslatorInterface */
    private $translator;

    /** @var  string */
    private $encryptedPassword;

    /**
     * @param Adapter $adapter
     * @param TranslatorInterface $translator
     */
    function __construct(Adapter $adapter, TranslatorInterface $translator)
    {
        $this->dbAdapter = $adapter;
        $this->passOutdated = time() - (60 * 60 * 24 * 30 * 6);// seconds * minutes * hours * days/month * months = 6 months
        $this->translator = $translator;
    }

    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function changeTo(User $user, $password)
    {
        $this->user = $user;
        $this->newPassword = $password;

        if ($this->isInHistory()) {
            $this->errorCode = 400;
            $this->message = $this->translator->translate('The provided password was already in use');
            return false;
        }
        if ($this->isPasswordContainUsername()) {
            $this->errorCode = 400;
            $this->message = $this->translator->translate('The provided password include the username in it');
            return false;
        }

        $this->updatePassword()->updateHistory()->cleanHistory();
        return true;
    }

    /**
     * Was the new password already used lately ?
     * @return boolean
     * @throws \RuntimeException
     */
    private function isInHistory()
    {
        $sql = <<<snip
            SELECT timestamp, password FROM ign_password_history
            WHERE user_id = ? AND timestamp > ?
            ORDER BY timestamp DESC LIMIT 3
snip;
        try {
            $results = $this->getAdapter()->query($sql)->execute([$this->user->getId(), $this->passOutdated]);
        } catch (\Exception $e) {
            throw new \RuntimeException('ChangePassword service failed to fetch history', 125902, $e);
        }
        $results->buffer();
        if ($results->count() === 0) {
            return;
        }
        return $this->MatchPassword($results);
    }

    /**
     * @return Adapter
     */
    private function getAdapter()
    {
        return $this->dbAdapter;
    }

    /**
     * @param ResultInterface $passwords
     * @return bool
     */
    private function MatchPassword(ResultInterface $passwords)
    {
        foreach ($passwords as $result) {
            list($pass, $salt) = explode(":", $result["password"]);
            $testCrypt = $this->generateJoomlaOneDotFivePassword($this->newPassword, $salt);
            if (strcmp($testCrypt, $pass) === 0) {
                return true;
            }
        }
    }

    /**
     * @param string $plainPass
     * @param string $salt
     * @return string
     */
    private function generateJoomlaOneDotFivePassword($plainPass, $salt)
    {
        return md5($plainPass . $salt);
    }

    /**
     * @return boolean
     */
    private function isPasswordContainUsername()
    {
        if (strstr($this->newPassword, $this->user->getUsername())) {
            return true;
        }
    }

    /**
     * @return ChangePassword
     * @throws \RuntimeException
     */
    private function cleanHistory()
    {
        $sql = "DELETE FROM ign_password_history WHERE timestamp < ?";
        try {
            $this->getAdapter()->query($sql)->execute([$this->passOutdated]);
        } catch (\Exception $e) {
            throw new \RuntimeException('ChangePassword service failed to purge history', 125902, $e);
        }
        return $this;
    }

    /**
     * @return ChangePassword
     * @throws \RuntimeException
     */
    private function updateHistory()
    {
        $sql = "INSERT INTO ign_password_history (user_id, password, timestamp) VALUES (?, ?, ?)";
        try {
            $this->getAdapter()->query($sql)->execute([$this->user->getId(), $this->getEncryptPassword(), time()]);
        } catch (\Exception $e) {
            throw new \RuntimeException('ChangePassword service failed to insert history', 125902, $e);
        }
        return $this;
    }

    /**
     * @return string
     */
    private function getEncryptPassword()
    {
        if (!$this->encryptedPassword) {
            $salt = $this->generateSalt();
            $this->encryptedPassword = ($this->generateJoomlaOneDotFivePassword($this->newPassword,
                    $salt) . ":" . $salt);
        }
        return $this->encryptedPassword;
    }

    /**
     * Return a randomly generated 32 bytes string
     * @return string
     */
    private function generateSalt()
    {
        return (Rand::getString(32, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', true));
    }

    /**
     * @throws \RuntimeException
     */
    private function updatePassword()
    {
        $sql = "UPDATE  ign_users SET password=? WHERE id=?";
        try {
            $this->getAdapter()->query($sql)->execute([$this->getEncryptPassword(), $this->user->getId()]);
        } catch (\Exception $e) {
            throw new \RuntimeException('ChangePassword service failed to update DB', 125901, $e);
        }
        return $this;
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
    public function getLastErrorMessage()
    {
        return $this->message;
    }
}