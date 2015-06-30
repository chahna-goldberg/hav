<?php

namespace BrainpopUser\Mapper\Reports;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class ListUsersGroupAssociation extends ResultSet
{
    protected $sql = <<<sql_code
        SELECT user_id, username, first_name || ' ' || last_name AS name, role
            , (SELECT true FROM group_users WHERE user_id=u.user_id AND group_id=? LIMIT 1 ) AS is_associated
        FROM users AS u WHERE u.account_id=? ORDER BY user_id
sql_code;

    /**
     * Execute the script and initilize the users list
     * @param int $accountId
     * @param int $groupId
     * @param Adapter $dbAdapter
     */
    function __construct($accountId, $groupId, Adapter $dbAdapter)
    {
        parent::__construct();
        $stmt   = $dbAdapter->query($this->sql);
        $result = $stmt->execute(array($groupId, $accountId));
        $this->buffer()->initialize($result);
    }

    /**
     * Return true if user is subscribed to the current group
     * @param int $userId
     * @return boolean
     */
    public function isSubscribed($userId)
    {
        foreach ($this->toArray() as $user) {
            if ($user['user_id'] == $userId && $user['is_associated']) {
                return true;
            }
        }
    }

}
