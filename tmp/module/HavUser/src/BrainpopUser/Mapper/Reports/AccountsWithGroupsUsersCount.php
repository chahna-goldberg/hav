<?php

namespace BrainpopUser\Mapper\Reports;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class AccountsWithGroupsUsersCount extends ResultSet
{
    protected $sql = 'SELECT * FROM v_accounts_with_groups_and_users_count';

    /**
     * Execute the script and initilize the users list
     * @param int $accountId
     * @param Adapter $dbAdapter
     */
    function __construct(Adapter $dbAdapter)
    {
        parent::__construct();
        $stmt   = $dbAdapter->query($this->sql);
        $result = $stmt->execute();
        $this->buffer()->initialize($result);
    }

}
