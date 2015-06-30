<?php

namespace BrainpopUser\Mapper;

use Application\Mapper\AbstractDbMapper;
use Application\Entity\DomainCollection;
use Application\Service\ObjectWatcher;

use BrainpopUser\Entity\User;
use BrainpopUser\Entity\InputFilter\AddUserFilter;
use BrainpopUser\Entity\InputFilter\UpdateUserFilter;

class UserMapper extends AbstractDbMapper
{

    /**
     * Set the entity that will be used by this mapper
     * @throws \RuntimeException
     */
    protected function setEntityPrototype()
    {
        if ($this->entityPrototype instanceof User) {
            throw new \RuntimeException('Entity prototype is already set and it is imutable');
        }
        $this->entityPrototype = new User;
    }

    /**
     * Return user by its ID
     * @param int $id
     * @return Account;
     */
    public function findById($id)
    {
        $cachedEntity   = $this->getFromMap($id);
        if ($cachedEntity) {
            return $cachedEntity;
        }
        $sql    = 'SELECT user_id AS id, account_id AS "accountId", username, email, first_name AS "firstName", last_name AS "lastName", role, user_status AS status '
                . 'FROM users WHERE user_id=?';
        $result = $this->getAdapter()->query($sql)->execute(array($id));
        if ($result->count() === 0) {
            return;
        }
        return $this->hydrateEntity($result->current());
    }

    /**
     * Fetch all user that related to specific account from the DB
     * @param int $accountId
     * @return DomainCollection
     */
    public function fetchAllByAccount($accountId)
    {
        $key            = "UserMapper.fetchAllByAccount.{$accountId}";
        $cachedEntity   = ObjectWatcher::collectionGet($key);
        if ($cachedEntity) {
            return $cachedEntity;
        }
        $sql    = 'SELECT user_id AS id, account_id AS "accountId", username, email, first_name AS "firstName", last_name AS "lastName", role, user_status AS status '
                . "FROM users WHERE account_id=? ORDER BY username";
        $results    = $this->getAdapter()->query($sql)->execute(array($accountId));
        return $this->populateResultSet($results, $key);
    }

    /**
     * Fetch users that related to specific role
     * @param array $roles
     * @return DomainCollection
     */
    public function fetchByRoles($roles)
    {
        $key            = "UserMapper.fetchByRoles." . crc32(implode('', $roles));
        $inQuery        = implode(',', array_fill(0, count($roles), '?'));
        $cachedEntity   = ObjectWatcher::collectionGet($key);
        if ($cachedEntity) {
            return $cachedEntity;
        }
        $sql    = 'SELECT user_id AS id, account_id AS "accountId", username, email, first_name AS "firstName", last_name AS "lastName", role, user_status AS status '
                . "FROM users WHERE role IN ({$inQuery}) ORDER BY username";
        $results    = $this->getAdapter()->query($sql)->execute($roles);
        return $this->populateResultSet($results, $key);
    }

    /**
     * Add the user data to the DB
     * @param User $user
     */
    public function doInsert(User $user) {
        $sql    = "INSERT INTO users (account_id, username, password, email, first_name, last_name, role, user_status)
                    VALUES (:accountId, :username, crypt(:password::text, gen_salt('md5')), :email, :firstName, :lastName, :role, :status) RETURNING user_id AS id";
        $var    = self::getSelectiveArrayFromEntity($user, array('accountId', 'username', 'password', 'email', 'firstName', 'lastName', 'role', 'status'));
        $result = $this->getAdapter()->query($sql)->execute($var)->current();
        $user->setId($result['id']);
    }

    /**
     * Update the user record in the DB
     * @param User $user
     */
    public function doUpdate(User $user) {
        if ($user->getPassword()) {
            $sql = "UPDATE users SET username=:username, password=crypt(:password::text, gen_salt('md5')), email=:email, first_name=:firstName, last_name=:lastName, role=:role, user_status=:status WHERE user_id=:id";
            $var = self::getSelectiveArrayFromEntity($user, array('username', 'password', 'email', 'firstName', 'lastName', 'role', 'status', 'id'));
        }
        else {
            $sql = "UPDATE users SET username=:username, email=:email, first_name=:firstName, last_name=:lastName, role=:role, user_status=:status WHERE user_id=:id";
            $var = self::getSelectiveArrayFromEntity($user, array('username', 'email', 'firstName', 'lastName', 'role', 'status', 'id'));
        }
        $this->getAdapter()->query($sql)->execute($var);
    }

    /**
     * Delete the User from the DB
     * @param User $user
     */
    public function doDelete(User $user)
    {
        $statement = $this->adapter->query("DELETE FROM users WHERE user_id=?");
        $statement->execute( array($user->getId()) );
    }

    /**
     * 
     * @param User $user
     * @throws \RuntimeException
     */
    public function isValid($user)
    {
        if (!$user instanceof $this->entityPrototype) {
            throw new \RuntimeException('Validate is missing the required entity');
        }

        $inputFilter = $this->getInputFilter($user);
        $inputFilter->setData($user->getArrayCopy());
        if ($inputFilter->isValid()) {
            $user->exchangeArray($inputFilter->getValues());
            return true;
        }
        $user->setMessages($inputFilter->getMessages());
    }

    /**
     * Return the aproperiate InputFilter
     * @param User $user
     * @return AddUserFilter|UpdateUserFilter
     */
    private function getInputFilter($user)
    {
        $accountRepo = new \BrainpopUser\Repository\AccountRepository();
        if ($user->getId()) {
            return new UpdateUserFilter($user->getAccountId(), $this->getAdapter(), $user->getId());
        }
        return new AddUserFilter($user->getAccountId(), $accountRepo, $this->getAdapter());
    }
}