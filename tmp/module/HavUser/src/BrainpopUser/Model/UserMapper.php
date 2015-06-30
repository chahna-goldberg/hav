<?php
namespace BrainpopUser\Model;

use Zend\Db\Adapter\Adapter;

class UserMapper
{
    protected $adapter;

    /**
     * Make the Adapter object avilable as local prtected variable
     * @param Adapter $adapter - DB PDO PgSQL conn
     */
    public function __construct(Adapter $adapter = null)
    {
        $this->adapter = $adapter;
    }
    
    /**
     * Check user /account / password combination for auth
     * @param string $account_id
     * @param string $username
     * @param string $password
     * @return object on success
     */
    public function authUser($account_id, $username, $password)
    {
        $sql = "SELECT user_id, account_id, username, role FROM users WHERE user_status='active' AND account_id=? AND username=? AND password=crypt(?, password)";
        $statement = $this->adapter->query($sql);
        $res = $statement->execute(array($account_id, $username, $password))->current();

        if (empty($res)) {
            return null;
        }
        return (object) $res;
    }

    /**
     * list users that are allowed to shared with
     * @return array
     */
    public function listShareableUsers($account_id, $user_id)
    {
        $sql = "SELECT user_id, username, email, first_name, last_name FROM users WHERE user_status='active' AND account_id=? AND user_id!=?";
        $statement = $this->adapter->query($sql);
        $res = $statement->execute(array($account_id, $user_id));

        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        
        return $rows;
    }
}