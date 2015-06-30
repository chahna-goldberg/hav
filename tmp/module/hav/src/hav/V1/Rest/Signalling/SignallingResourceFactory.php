<?php
namespace hav\V1\Rest\Signalling;

class SignallingResourceFactory
{
    public function __invoke($services)
    {
        return new SignallingResource();
    }
}
