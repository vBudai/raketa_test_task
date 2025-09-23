<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Db;

use Raketa\BackendTestTask\Infrastructure\Db\Exception\RedisConnectorException;
use Redis;
use RedisException;

class RedisConnectorBuilder
{
    public function __construct(
        private readonly string $host,
        private readonly int $port = 6379,
        public readonly ?string $password = null,
        public readonly ?int $dbindex = null,
    ) {}

    /**
     * @throws RedisConnectorException
     */
    public function build(): RedisConnector
    {
        try{
            $redis = $this->connectRedis();
            return new RedisConnector($redis);
        } catch (RedisException $e){
            throw new RedisConnectorException($e->getMessage(), $e->getCode(), $e);
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
