<?php
namespace Authenticate\V1\Rpc\Logout;

class LogoutControllerFactory
{
    public function __invoke($controllers)
    {
        return new LogoutController();
    }
}
