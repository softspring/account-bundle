<?php

namespace Softspring\AccountBundle\Controller\Admin;

use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\UserBundle\Controller\Traits\DispatchTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountsController extends AbstractController
{
    use DispatchTrait;

    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * AccountsController constructor.
     *
     * @param AccountManagerInterface     $accountManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(AccountManagerInterface $accountManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->accountManager = $accountManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request): Response
    {
        $repo = $this->accountManager->getRepository();

        $accounts = $repo->findBy([]);

        return $this->render('@SfsAccount/admin/accounts/list.html.twig', [
            'accounts' => $accounts,
        ]);
    }
}