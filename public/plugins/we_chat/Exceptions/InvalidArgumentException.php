<?php

namespace plugins\we_chat\Exceptions;

/**
 * 接口参数异常
 * Class InvalidArgumentException
 * @package WeChat
 */
class InvalidArgumentException extends \Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * InvalidArgumentException constructor.
     * @param string $message
     * @param integer $code
     * @param array $raw
     */
    public function __construct($message, $code = 0, $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}