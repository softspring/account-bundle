<?php

namespace Softspring\AccountBundle\Controller\Settings;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\MultiAccountedAccountInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * @var string
     */
    protected $accountParameterName;

    /**
     * UsersController constructor.
     *
     * @param AccountManagerInterface $accountManager
     * @param string                  $accountParameterName
     */
    public function __construct(AccountManagerInterface $accountManager, string $accountParameterName)
    {
        $this->accountManager = $accountManager;
        $this->accountParameterName = $accountParameterName;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request): Response
    {
        /** @var AccountInterface $account */
        $account = $request->attributes->get($this->accountParameterName);

        if ($account instanceof MultiAccountedAccountInterface) {
            $relations = $account->getRelations();
        }

        $viewData = new \ArrayObject([
            'relations' => $relations ?? [],
            'account' => $account,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsAccountEvents::SETTINGS_USERS_LIST_VIEW);

        return $this->render('@SfsAccount/settings/users/list.html.twig', $viewData->getArrayCopy());
    }
}