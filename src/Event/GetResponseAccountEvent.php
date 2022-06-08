<?php

namespace Softspring\AccountBundle\Event;

use Softspring\Component\Events\GetResponseEventInterface;
use Softspring\Component\Events\GetResponseTrait;

class GetResponseAccountEvent extends AccountEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}
