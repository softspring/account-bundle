<?php

namespace Softspring\AccountBundle\Controller\Settings;

use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\Form\SettingsFormInterface;
use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\CoreBundle\Controller\Traits\DispatchGetResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends AbstractController
{
    use DispatchGetResponseTrait;

    protected AccountManagerInterface $accountManager;

    protected SettingsFormInterface $settingsForm;

    protected string $accountParameterName;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(AccountManagerInterface $accountManager, SettingsFormInterface $settingsForm, string $accountParameterName, EventDispatcherInterface $eventDispatcher)
    {
        $this->accountManager = $accountManager;
        $this->settingsForm = $settingsForm;
        $this->accountParameterName = $accountParameterName;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function settings(Request $request): Response
    {
        $account = $request->attributes->get($this->accountParameterName);

        $formOptions = method_exists($this->settingsForm, 'formOptions') ? $this->settingsForm->formOptions($account, $request) : ['method' => 'POST'];
        $form = $this->createForm(get_class($this->settingsForm), $account, $formOptions)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::SETTINGS_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $this->accountManager->save($account);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::SETTINGS_UPDATED, new GetResponseAccountEvent($account, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_account_settings_general');
            } else {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::SETTINGS_FORM_INVALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }
            }
        }

        return $this->render('@SfsAccount/settings/settings/settings.html.twig', [
            'settings_form' => $form->createView(),
        ]);
    }
}
