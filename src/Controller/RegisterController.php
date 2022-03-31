<?php

namespace Softspring\AccountBundle\Controller;

use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Form\RegisterFormInterface;
use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\Component\CrudlController\Event\GetResponseFormEvent;
use Softspring\CoreBundle\Controller\Traits\DispatchGetResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{
    use DispatchGetResponseTrait;

    protected AccountManagerInterface $accountManager;

    protected EventDispatcherInterface $eventDispatcher;

    protected RegisterFormInterface $registerForm;

    public function __construct(AccountManagerInterface $accountManager, EventDispatcherInterface $eventDispatcher, RegisterFormInterface $registerForm)
    {
        $this->accountManager = $accountManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->registerForm = $registerForm;
    }

    public function register(Request $request): Response
    {
        $account = $this->accountManager->createEntity();

        if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_INITIALIZE, new GetResponseAccountEvent($account, $request))) {
            return $response;
        }

        $formOptions = method_exists($this->registerForm, 'formOptions') ? $this->registerForm->formOptions($account, $request) : ['method' => 'POST'];
        $form = $this->createForm(get_class($this->registerForm), $account, $formOptions)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $this->accountManager->saveEntity($account);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_SUCCESS, new GetResponseAccountEvent($account, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_account_register_success', ['account' => $account]);
            } else {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_FORM_INVALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }
            }
        }

        return $this->render('@SfsAccount/register/register.html.twig', [
            'register_form' => $form->createView(),
        ]);
    }

    public function success(AccountInterface $account, Request $request): Response
    {
        return $this->render('@SfsAccount/register/success.html.twig', [
            'account' => $account,
        ]);
    }
}
