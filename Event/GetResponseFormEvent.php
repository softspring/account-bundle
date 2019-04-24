<?php

namespace Softspring\AccountBundle\Event;

class GetResponseFormEvent extends FormEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}