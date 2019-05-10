<?php

namespace Softspring\AccountBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseEventInterface;
use Softspring\ExtraBundle\Event\GetResponseTrait;

class GetResponseAccountEvent extends AccountEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}