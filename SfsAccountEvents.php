<?php

namespace Softspring\AccountBundle;

class SfsAccountEvents
{
    /**
     * @Event("Softspring\AccountBundle\Event\AccountEvent")
     */
    const ACCOUNT_CREATED = 'sfs_account.account.created';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    const REGISTER_INITIALIZE = 'sfs_account.register.initialize' ;

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    const REGISTER_FORM_VALID = 'sfs_account.register.form_valid' ;

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    const REGISTER_SUCCESS = 'sfs_account.register.success' ;

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    const REGISTER_FORM_INVALID = 'sfs_account.register.form_invalid' ;

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseAccountEvent")
     */
    const SETTINGS_UPDATED = 'sfs_account.settings.updated';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    const SETTINGS_FORM_VALID = 'sfs_account.settings.form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    const SETTINGS_FORM_INVALID = 'sfs_account.settings.form_invalid';

    /**
     * @Event("Softspring\AccountBundle\Event\ViewEvent")
     */
    const ADMIN_ACCOUNTS_DETAILS_VIEW = 'sfs_account.admin.accounts.details_view';
}