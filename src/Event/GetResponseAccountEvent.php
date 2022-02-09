<?php

namespace Softspring\AccountBundle\Event;

use Softspring\CoreBundle\Event\GetResponseEventInterface;
use Softspring\CoreBundle\Event\GetResponseTrait;

class GetResponseAccountEvent extends AccountEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}
