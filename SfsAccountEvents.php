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
    const PREFERENCES_UPDATED = 'sfs_account.preferences.updated';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    const PREFERENCES_FORM_VALID = 'sfs_account.preferences.form_valid';

    /**
     * @Event("Softspring\AccountBundle\Event\GetResponseFormEvent")
     */
    const PREFERENCES_FORM_INVALID = 'sfs_account.preferences.form_invalid';
}