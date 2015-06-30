<?php
namespace Authenticate\V1\Rpc\ResetPassword;

class ResetPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        return new ResetPasswordController();
    }
}
