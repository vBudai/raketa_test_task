<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Redis;
use RedisException;

class ConnectorBuilder
{
    public function __construct(
        private string $host,
        private int $port = 6379,
        public ?string $password = null,
        public ?int $dbindex = null,
    ) {}

    /**
     * @throws ConnectorException
     */
    public function build(): Connector
    {
        try{
            $redis = $this->connectRedis();
            return new Connector($redis);
        } catch (RedisException $e){
            throw new ConnectorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws RedisException
     */
    protected function connectRedis(): Redis
    {
        $redis = new Redis();

        $isConnected = $redis->isConnected();
        if (!$isConnected && $redis->ping('Pong')) {
            $isConnected = $redis->connect(
                $this->host,
                $this->port,
            );
        }

        if(!$isConnected){
            throw new RedisException('Connection to Redis failed: ' . $redis->getLastError(), 500, null);
        }

        $redis->auth($this->password);
        $redis->select($this->dbindex);

        return $redis;
    }
}
