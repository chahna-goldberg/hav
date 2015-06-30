<?php
namespace Authenticate\V1\Rpc\SetGarden;

class SetGardenControllerFactory
{
    public function __invoke($controllers)
    {
        return new SetGardenController();
    }
}
