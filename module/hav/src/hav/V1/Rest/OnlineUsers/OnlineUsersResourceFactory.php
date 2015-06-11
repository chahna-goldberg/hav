<?php
namespace hav\V1\Rest\OnlineUsers;

class OnlineUsersResourceFactory
{
    public function __invoke($services)
    {
        return new OnlineUsersResource();
    }
}
