<?php

namespace Softspring\AccountBundle\Controller\Settings;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\Form\SettingsFormInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\CoreBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends AbstractController
{
    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * @var SettingsFormInterface
     */
    protected $settingsForm;

    /**
     * @var string
     */
    protected $accountParameterName;

    /**
     * SettingsController constructor.
     *
     * @param AccountManagerInterface $accountManager
     * @param SettingsFormInterface   $settingsForm
     * @param string                  $accountParameterName
     */
    public function __construct(AccountManagerInterface $accountManager, SettingsFormInterface $settingsForm, string $accountParameterName)
    {
        $this->accountManager = $accountManager;
        $this->settingsForm = $settingsForm;
        $this->accountParameterName = $accountParameterName;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
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