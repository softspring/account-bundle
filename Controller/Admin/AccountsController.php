<?php

namespace Softspring\AccountBundle\Controller\Admin;

use Jhg\DoctrinePagination\ORM\PaginatedRepository;
use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\AccountBundle\Event\ViewEvent;
use Softspring\AccountBundle\Form\Admin\AccountForm;
use Softspring\AccountBundle\Form\Admin\AccountListFilterFormInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\ExtraBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountsController extends AbstractController
{
    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var AccountListFilterFormInterface
     */
    protected $listFilterForm;

    /**
     * AccountsController constructor.
     * @param AccountManagerInterface $accountManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param AccountListFilterFormInterface $listFilterForm
     */
    public function __construct(AccountManagerInterface $accountManager, EventDispatcherInterface $eventDispatcher, AccountListFilterFormInterface $listFilterForm)
    {
        $this->accountManager = $accountManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->listFilterForm = $listFilterForm;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request): Response
    {
        // additional fields for pagination and sorting
        $page = $this->listFilterForm->getPage($request);
        $rpp = $this->listFilterForm->getRpp($request);
        $orderSort = $this->listFilterForm->getOrder($request);

        // filter form
        $form = $this->createForm(get_class($this->listFilterForm))->handleRequest($request);
        $filters = $form->isSubmitted() && $form->isValid() ? array_filter($form->getData()) : [];

        // get results
        $repo = $this->accountManager->getRepository();
        if ($repo instanceof PaginatedRepository) {
            $accounts = $repo->findPageBy($page, $rpp, $filters, $orderSort);
        } else {
            $accounts = $repo->findBy($filters, $orderSort, $rpp, ($page-1)*$rpp);
        }

        // show view
        $viewData = new \ArrayObject([
            'accounts' => $accounts,
            'filterForm' => $form->createView(),
        ]);

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::ADMIN_ACCOUNTS_LIST_VIEW);

        if ($request->isXmlHttpRequest()) {
            return $this->render('@SfsAccount/admin/accounts/list-page.html.twig', $viewData->getArrayCopy());
        } else {
            return $this->render('@SfsAccount/admin/accounts/list.html.twig', $viewData->getArrayCopy());
        }
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

        $viewData = new \ArrayObject([
            'account' => $account,
            'multi_accounted_account' => $account instanceof MultiAccountedAccountInterface,
        ]);

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::ADMIN_ACCOUNTS_DETAILS_VIEW);

        return $this->render('@SfsAccount/admin/accounts/details.html.twig', $viewData->getArrayCopy());
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