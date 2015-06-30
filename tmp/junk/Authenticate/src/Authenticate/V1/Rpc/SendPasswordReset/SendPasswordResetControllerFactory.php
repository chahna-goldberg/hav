<?php
namespace Authenticate\V1\Rpc\SendPasswordReset;

class SendPasswordResetControllerFactory
{
    public function __invoke($controllers)
    {
        return new SendPasswordResetController();
    }
}
