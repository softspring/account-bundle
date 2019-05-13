<?php

namespace Softspring\AccountBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseEventInterface;
use Softspring\ExtraBundle\Event\GetResponseTrait;

class GetResponseFormEvent extends FormEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}