<?php

namespace Softspring\AccountBundle\Controller\Admin;

use Jhg\DoctrinePagination\ORM\PaginatedRepository;
use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\Account\Model\AccountInterface;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\Event\ViewEvent;
use Softspring\AccountBundle\Form\Admin\AccountCreateFormInterface;
use Softspring\AccountBundle\Form\Admin\AccountDeleteFormInterface;
use Softspring\AccountBundle\Form\Admin\AccountListFilterFormInterface;
use Softspring\AccountBundle\Form\Admin\AccountUpdateFormInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\ExtraBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
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
     * @var AccountCreateFormInterface
     */
    protected $createForm;

    /**
     * @var AccountUpdateFormInterface
     */
    protected $updateForm;

    /**
     * @var AccountDeleteFormInterface
     */
    protected $deleteForm;

    /**
     * AccountsController constructor.
     * @param AccountManagerInterface $accountManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param AccountListFilterFormInterface $listFilterForm
     * @param AccountCreateFormInterface $createForm
     * @param AccountUpdateFormInterface $updateForm
     * @param AccountDeleteFormInterface $deleteForm
     */
    public function __construct(AccountManagerInterface $accountManager, EventDispatcherInterface $eventDispatcher, AccountListFilterFormInterface $listFilterForm, AccountCreateFormInterface $createForm, AccountUpdateFormInterface $updateForm, AccountDeleteFormInterface $deleteForm)
    {
        $this->accountManager = $accountManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->listFilterForm = $listFilterForm;
        $this->createForm = $createForm;
        $this->updateForm = $updateForm;
        $this->deleteForm = $deleteForm;
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
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $newAccount = $this->accountManager->create();

        if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_INITIALIZE, new GetResponseAccountEvent($newAccount, $request))) {
            return $response;
        }

        $form = $this->createForm(get_class($this->createForm), $newAccount, ['method' => 'POST'])->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $this->accountManager->save($newAccount);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_SUCCESS, new GetResponseAccountEvent($newAccount, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_user_register_success');
            } else {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_FORM_INVALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }
            }
        }

        // show view
        $viewData = new \ArrayObject([
            'form' => $form->createView(),
        ]);

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_VIEW);

        return $this->render('@SfsAccount/admin/accounts/create.html.twig', $viewData->getArrayCopy());
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

        if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_UPDATE_INITIALIZE, new GetResponseAccountEvent($account, $request))) {
            return $response;
        }

        $form = $this->createForm(get_class($this->updateForm), $account, ['method' => 'POST'])->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_UPDATE_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $this->accountManager->save($account);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_UPDATE_SUCCESS, new GetResponseAccountEvent($account, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_account_admin_accounts_details', ['account' => $account->getId()]);
            } else {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_UPDATE_FORM_INVALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }
            }
        }

        // show view
        $viewData = new \ArrayObject([
            'form' => $form->createView(),
            'account' => $account,
        ]);

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::ADMIN_ACCOUNTS_UPDATE_VIEW);

        return $this->render('@SfsAccount/admin/accounts/update.html.twig', $viewData->getArrayCopy());
    }

    /**
     * @param string  $account
     * @param Request $request
     *
     * @return Response
     */
    public function delete(string $account, Request $request): Response
    {
        $account = $this->accountManager->findAccountBy(['id' => $account]);

        if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_INITIALIZE, new GetResponseAccountEvent($account, $request))) {
            return $response;
        }

        $form = $this->getDeleteForm($account)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $this->accountManager->delete($account);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_SUCCESS, new GetResponseAccountEvent($account, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_account_admin_accounts_list');
            } else {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_FORM_INVALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }
            }
        }

        // show view
        $viewData = new \ArrayObject([
            'form' => $form->createView(),
            'account' => $account,
        ]);

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_VIEW);

        return $this->render('@SfsAccount/admin/accounts/delete.html.twig', $viewData->getArrayCopy());
    }

    protected function getDeleteForm(AccountInterface $account): FormInterface
    {
        return $this->createForm(get_class($this->deleteForm), $account, ['method' => 'POST']);
    }
}