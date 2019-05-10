<?php

namespace Softspring\AccountBundle\Controller;

use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Form\RegisterFormInterface;
use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\ExtraBundle\Controller\AbstractController;
use Softspring\User\Model\OwnerInterface;
use Softspring\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
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
     * @var RegisterFormInterface
     */
    protected $registerForm;

    /**
     * RegisterController constructor.
     *
     * @param AccountManagerInterface  $accountManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param RegisterFormInterface    $registerForm
     */
    public function __construct(AccountManagerInterface $accountManager, EventDispatcherInterface $eventDispatcher, RegisterFormInterface $registerForm)
    {
        $this->accountManager = $accountManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->registerForm = $registerForm;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request): Response
    {
        $account = $this->accountManager->create();

        if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_INITIALIZE, new GetResponseAccountEvent($account, $request))) {
            return $response;
        }

        $form = $this->createForm(get_class($this->registerForm), $account, ['method' => 'POST'])->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $user = $this->getUser();

                if ($user instanceof UserInterface) {
                    if ($account instanceof OwnerInterface) {
                        $account->setOwner($user);
                    }
                }

                $this->accountManager->save($account);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::REGISTER_SUCCESS, new GetResponseAccountEvent($account, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_account_register_success');
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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function success(Request $request): Response
    {
        return $this->render('@SfsAccount/register/success.html.twig', [

        ]);
    }
}