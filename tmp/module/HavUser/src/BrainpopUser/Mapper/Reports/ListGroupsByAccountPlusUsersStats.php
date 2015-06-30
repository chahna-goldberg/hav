<?php

namespace BrainpopUser\Mapper\Reports;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class ListGroupsByAccountPlusUsersStats extends ResultSet
{
    protected $sql = <<<sql_code
        WITH account_users AS (
            SELECT * FROM users AS u LEFT JOIN group_users AS gu USING(user_id) WHERE account_id=13
        )
        SELECT g.id, g.name, g.serial, g.invites_bank,
            ( SELECT COUNT(*) FROM account_users WHERE group_id=g.id AND role='student' ) AS total_students,
            ( SELECT first_name||' '||last_name FROM account_users WHERE group_id=g.id AND role='teacher' LIMIT 1 ) AS teacher,
            ( SELECT count(*) FROM account_users WHERE group_id=g.id AND role='teacher' ) AS total_teacher
        FROM groups AS g WHERE account_id=? ORDER by g.name
sql_code;

    /**
     * Execute the script and initilize the groups list
     * @param int $accountId
     * @param int $groupId
     * @param Adapter $dbAdapter
     */
    function __construct($accountId, Adapter $dbAdapter)
    {
        parent::__construct();
        $stmt   = $dbAdapter->query($this->sql);
        $result = $stmt->execute(array($accountId));
        $this->buffer()->initialize($result);
    }

}
