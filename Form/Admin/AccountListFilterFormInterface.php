<?php

namespace Softspring\AccountBundle\Form\Admin;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

interface AccountListFilterFormInterface extends FormTypeInterface
{
    /**
     * @param Request $request
     * @return int
     */
    public function getPage(Request $request): int;

    /**
     * @param Request $request
     * @return int
     */
    public function getRpp(Request $request): int;

    /**
     * @param Request $request
     * @return array
     */
    public function getOrder(Request $request): array;

    /**
     * @return string
     */
    public static function getPageParamName(): string;

    /**
     * @return string
     */
    public static function getRppParamName(): string;

    /**
     * @return string
     */
    public static function getOrderFieldParamName(): string;

    /**
     * @return string
     */
    public static function getOrderDirectionParamName(): string;
}