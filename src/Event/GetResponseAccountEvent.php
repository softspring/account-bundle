<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Event;

use Softspring\Component\Events\GetResponseEventInterface;
use Softspring\Component\Events\GetResponseTrait;

class GetResponseAccountEvent extends AccountEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}
