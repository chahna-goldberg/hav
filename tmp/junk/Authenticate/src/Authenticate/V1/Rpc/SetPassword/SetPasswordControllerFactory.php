<?php
namespace Authenticate\V1\Rpc\SetPassword;

class SetPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        return new SetPasswordController();
    }
}
