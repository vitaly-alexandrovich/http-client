<?php

namespace HttpClient\testcase;

use HttpClient\Response;
use Throwable;

class TestCase implements TestCaseInterface
{
    /** @var Throwable */
    protected $exception       = null;
    protected $replaceResponse = true;
    protected $response        = null;
    protected $options         = [];


    /**
     * Устанавливаем исключение
     * @param Throwable $exception
     */
    public function setException(Throwable $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Убираем исключение
     */
    public function unsetException()
    {
        $this->exception = null;
    }

    /**
     * @inheritdoc
     */
    public function throwException()
    {
        if (isset($this->exception) && $this->exception instanceof Throwable) {
            throw $this->exception;
        }
    }

    /**
     * Устанавливаем ответ, который должны вернуть
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response        = $response;
        $this->replaceResponse = true;
    }

    /**
     * Отменяет режим подмены ответа
     */
    public function unsetResponse()
    {
        $this->response        = null;
        $this->replaceResponse = false;
    }

    /**
     * @inheritdoc
     */
    public function mustReplaceResponse()
    {
        return $this->replaceResponse;
    }

    /**
     * @inheritdoc
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Устанавливаем опции запроса
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->options;
    }

}