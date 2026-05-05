<?php

namespace Softspring\AccountBundle\Controller\Settings;

use ArrayObject;
use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountMembershipsInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    protected AccountManagerInterface $accountManager;

    protected string $accountParameterName;

    public function __construct(AccountManagerInterface $accountManager, string $accountParameterName)
    {
        $this->accountManager = $accountManager;
        $this->accountParameterName = $accountParameterName;
    }

    public function list(Request $request): Response
    {
        /** @var AccountInterface $account */
        $account = $request->attributes->get($this->accountParameterName);

        if ($account instanceof AccountMembershipsInterface) {
            $memberships = $account->getMemberships();
        }

        $viewData = new ArrayObject([
            'memberships' => $memberships ?? [],
            'account' => $account,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::SETTINGS_USERS_LIST_VIEW);

        return $this->render('@SfsAccount/settings/users/list.html.twig', $viewData->getArrayCopy());
    }
}
