<?php

namespace Softspring\AccountBundle\Controller\User;

use Exception;
use Softspring\AccountBundle\Model\UserAccountMembershipsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AccountsController extends AbstractController
{
    public function list(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof UserAccountMembershipsInterface) {
            throw new Exception('Invalid user class');
        }

        return $this->render('@SfsAccount/user/accounts/list.html.twig', [
        ]);
    }
}
