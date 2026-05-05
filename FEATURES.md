# Account Bundle Features

Functional definition for `softspring/account-bundle`.

## Purpose

- Add an account ownership layer above users in Symfony applications.
- Let application data belong to accounts instead of directly to end users.
- Provide reusable account-aware flows for registration, settings, account selection, and admin management.

## Main Features

- Registers the base account model contracts and Doctrine mappings used to connect applications with concrete account entities.
- Resolves the current account from request attributes and exposes it to controllers, services, and Twig.
- Provides Doctrine filter support for account-scoped entities.
- Ships ready-made routes, controllers, forms, templates, and events for account registration, account settings, user account lists, and admin account CRUD.
- Adds security integration for account access checks through `CHECK_ACCOUNT_ACCESS`.

## Integration And Extension

- Builds on top of `softspring/user-bundle`.
- Supports custom account entities and optional account membership entities through bundle configuration.
- Can be extended with custom membership roles, voters, forms, event listeners, controllers, and templates.
- Is designed to be combined with account-scoped domain entities such as subscriptions, projects, invoices, customers, or content.

## Expected Capabilities

- Must work with the supported dependency matrix of this line, including Symfony `6.4`, `7.x`, and `8.x`.
- Must keep both regular and lowest dependency validation workflows working (`composer test` and `composer test-bc`).
- Must keep account resolution, account filtering, admin account permissions, and bundle configuration behavior stable across minor releases in the same line.
