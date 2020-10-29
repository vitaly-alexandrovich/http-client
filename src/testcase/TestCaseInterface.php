<?php

namespace HttpClient\testcase;

interface TestCaseInterface {

    /**
     * Симуляция исключений
     * @return void
     */
    function throwException();

    /**
     * Подмена настроек
     * @return array
     */
    function getOptions();

    /**
     * Подменяем ответ
     * @return mixed
     */
    function getResponse();

    /**
     * Нужно ли подменять ответ
     * @return bool
     */
    function mustReplaceResponse();
}