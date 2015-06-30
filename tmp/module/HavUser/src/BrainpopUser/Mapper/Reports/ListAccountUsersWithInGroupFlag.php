<?php

namespace BrainpopUser\Mapper\Reports;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class ListAccountUsersWithInGroupFlag extends ResultSet
{
    protected $sql = 'SELECT * FROM v_users_with_ingroup WHERE account_id=?';

    /**
     * Execute the script and initilize the users list
     * @param int $accountId
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
