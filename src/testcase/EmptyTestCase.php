<?php

namespace HttpClient\testcase;

/**
 * Class EmptyTestCase
 * Болванка, чтобы не проверять каждый раз, установлен ли какой-то ещё тестовый сценарий
 * @package testcase
 */
class EmptyTestCase implements TestCaseInterface {

    /**
     * @inheritdoc
     */
    public function throwException()
    {

    }

    /**
     * @inheritdoc
     */
    function getOptions()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    function getResponse()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    function mustReplaceResponse()
    {
        return false;
    }


}