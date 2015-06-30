<?php
namespace Authenticate\V1\Rpc\Login;

class LoginControllerFactory
{
    public function __invoke($controllers)
    {
        return new LoginController();
    }
}
