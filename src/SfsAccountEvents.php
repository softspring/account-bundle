<?php

namespace Softspring\AccountBundle;

use Symfony\Contracts\EventDispatcher\Event;

class SfsAccountEvents
{
    /**
     * @Event("Softspring\AccountBundle\Event\AccountEvent")
     */
    public const ACCOUNT_CREATED = 'sfs_account.account.created';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const REGISTER_INITIALIZE = 'sfs_account.register.initialize';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const REGISTER_FORM_VALID = 'sfs_account.register.form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const REGISTER_SUCCESS = 'sfs_account.register.success';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const REGISTER_FORM_INVALID = 'sfs_account.register.form_invalid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const SETTINGS_UPDATED = 'sfs_account.settings.updated';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const SETTINGS_FORM_VALID = 'sfs_account.settings.form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const SETTINGS_FORM_INVALID = 'sfs_account.settings.form_invalid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_LIST_INITIALIZE = 'sfs_account.admin.accounts.list_initialize';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_ACCOUNTS_LIST_VIEW = 'sfs_account.admin.accounts.list_view';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_DETAILS_INITIALIZE = 'sfs_account.admin.accounts.details_initialize';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_ACCOUNTS_DETAILS_VIEW = 'sfs_account.admin.accounts.details_view';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_CREATE_INITIALIZE = 'sfs_account.admin.accounts.create_initialize';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const ADMIN_ACCOUNTS_CREATE_FORM_VALID = 'sfs_account.admin.accounts.create_form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_CREATE_SUCCESS = 'sfs_account.admin.accounts.create_success';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const ADMIN_ACCOUNTS_CREATE_FORM_INVALID = 'sfs_account.admin.accounts.create_form_invalid';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_ACCOUNTS_CREATE_VIEW = 'sfs_account.admin.accounts.create_view';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_UPDATE_INITIALIZE = 'sfs_account.admin.accounts.update_initialize';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const ADMIN_ACCOUNTS_UPDATE_FORM_VALID = 'sfs_account.admin.accounts.update_form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_UPDATE_SUCCESS = 'sfs_account.admin.accounts.update_success';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const ADMIN_ACCOUNTS_UPDATE_FORM_INVALID = 'sfs_account.admin.accounts.update_form_invalid';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_ACCOUNTS_UPDATE_VIEW = 'sfs_account.admin.accounts.update_view';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_DELETE_INITIALIZE = 'sfs_account.admin.accounts.delete_initialize';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const ADMIN_ACCOUNTS_DELETE_FORM_VALID = 'sfs_account.admin.accounts.delete_form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    public const ADMIN_ACCOUNTS_DELETE_SUCCESS = 'sfs_account.admin.accounts.delete_success';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    public const ADMIN_ACCOUNTS_DELETE_FORM_INVALID = 'sfs_account.admin.accounts.delete_form_invalid';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_ACCOUNTS_DELETE_VIEW = 'sfs_account.admin.accounts.delete_view';
}
