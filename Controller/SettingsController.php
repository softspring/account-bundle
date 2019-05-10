<?php

namespace Softspring\AccountBundle\Controller;

use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Controller\Traits\GetAccountTrait;
use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\Form\SettingsFormInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\ExtraBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends AbstractController
{
    use GetAccountTrait;

    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * @var SettingsFormInterface
     */
    protected $settingsForm;

    /**
     * RegisterController constructor.
     *
     * @param AccountManagerInterface  $accountManager
     * @param SettingsFormInterface    $settingsForm
     */
    public function __construct(AccountManagerInterface $accountManager, SettingsFormInterface $settingsForm)
    {
        $this->accountManager = $accountManager;
        $this->settingsForm = $settingsForm;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function settings(Request $request): Response
    {
        $account = $this->getAccount();

        $form = $this->createForm(get_class($this->settingsForm), $account, ['method' => 'POST'])->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::SETTINGS_FORM_VALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }

                $this->accountManager->save($account);

                if ($response = $this->dispatchGetResponse(SfsAccountEvents::SETTINGS_UPDATED, new GetResponseAccountEvent($account, $request))) {
                    return $response;
                }

                return $this->redirectToRoute('sfs_account_settings');
            } else {
                if ($response = $this->dispatchGetResponse(SfsAccountEvents::SETTINGS_FORM_INVALID, new GetResponseFormEvent($form, $request))) {
                    return $response;
                }
            }
        }

        return $this->render('@SfsAccount/settings/settings.html.twig', [
            'settings_form' => $form->createView(),
        ]);
    }
}