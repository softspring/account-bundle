<?php

namespace Softspring\AccountBundle\Controller\Admin;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\CoreBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AccountsController extends AbstractController
{
    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * AccountsController constructor.
     *
     * @param AccountManagerInterface $accountManager
     */
    public function __construct(AccountManagerInterface $accountManager)
    {
        $this->accountManager = $accountManager;
    }

    public function accountsCountWidget(): Response
    {
        return $this->render('@SfsAccount/admin/accounts/widget-accounts-count.html.twig', [
            'accounts' => $this->accountManager->getRepository()->count([]),
        ]);
    }
}