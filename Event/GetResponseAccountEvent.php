<?php

namespace Softspring\AccountBundle\Event;

class GetResponseAccountEvent extends AccountEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}