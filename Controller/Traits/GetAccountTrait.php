<?php

namespace Softspring\AccountBundle\Controller\Traits;

use Softspring\Account\Model\AccountInterface;

trait GetAccountTrait
{
    /**
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface
    {
        return $this->get('request_stack')->getCurrentRequest()->attributes->get('_account');
    }
}