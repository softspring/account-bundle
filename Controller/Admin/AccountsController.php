<?php

namespace Softspring\AccountBundle\Controller\Admin;

use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\AccountBundle\Form\Admin\AccountForm;
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

    /**
     * @param string  $account
     * @param Request $request
     *
     * @return Response
     */
    public function details(string $account, Request $request): Response
    {
        $account = $this->accountManager->findAccountBy(['id' => $account]);

        return $this->render('@SfsAccount/admin/accounts/details.html.twig', [
            'account' => $account,
            'multi_accounted_account' => $account instanceof MultiAccountedAccountInterface,
        ]);
    }

    /**
     * @param string  $account
     * @param Request $request
     *
     * @return Response
     */
    public function update(string $account, Request $request): Response
    {
        $account = $this->accountManager->findAccountBy(['id' => $account]);

        $form = $this->createForm(AccountForm::class, $account, ['method' => 'POST'])->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->accountManager->save($account);

                return $this->redirectToRoute('sfs_account_admin_accounts_details', ['account' => $account]);
            }
        }

        return $this->render('@SfsAccount/admin/accounts/update.html.twig', [
            'update_form' => $form->createView(),
            'account' => $account,
        ]);
    }
}