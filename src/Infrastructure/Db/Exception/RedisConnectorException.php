<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Db\Exception;

class RedisConnectorException extends \Exception
{
    public function __construct(
        protected $message,
        protected $code,
        protected ?\Throwable $previous,
    ) {
        parent::__construct($this->message, $this->code, $this->previous);
    }

    public function __toString(): string
    {
        return sprintf(
            '[%s] %s in %s on line %d',
            $this->getCode(),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
        );
    }
}
