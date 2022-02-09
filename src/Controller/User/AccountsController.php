<?php

namespace Softspring\AccountBundle\Controller\User;

use Softspring\AccountBundle\Model\UserMultiAccountedInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AccountsController extends AbstractController
{
    public function list(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof UserMultiAccountedInterface) {
            throw new \Exception('Invalid user class');
        }

        return $this->render('@SfsAccount/user/accounts/list.html.twig', [

        ]);
    }
}