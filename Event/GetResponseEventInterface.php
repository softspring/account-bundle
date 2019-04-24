<?php

namespace Softspring\AccountBundle\Event;

use Symfony\Component\HttpFoundation\Response;

interface GetResponseEventInterface
{
    /**
     * @return Response|null
     */
    public function getResponse(): ?Response;
}