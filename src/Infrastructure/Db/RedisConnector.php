<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Db;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Db\Exception\RedisConnectorException;
use Redis;
use RedisException;

readonly class RedisConnector
{
    public function __construct(
        private Redis $redis
    ){
    }

    /**
     * @throws RedisConnectorException
     */
    public function get(string $key): ?Cart
    {
        try {
            $raw = $this->redis->get($key);
            if(!$raw){
                return null;
            }

            return Cart::fromArray((array)json_decode(
                json: $raw,
                associative: true,
                flags: JSON_THROW_ON_ERROR
            ));
        } catch (RedisException $e) {
            throw new RedisConnectorException('Connector error', $e->getCode(), $e);
        } catch (\JsonException $e) {
            throw new RedisConnectorException('Error due parsing json: ' . $e->getMessage() , $e->getCode(), $e);
        }
    }

    /**
     * @throws RedisConnectorException
     */
    public function set(string $key, Cart $value): void
    {
        try {
            $json = json_encode($value, JSON_THROW_ON_ERROR);

            $result = $this->redis->setex($key, 24 * 60 * 60, $json);
            if(!$result) {
                throw new RedisConnectorException(
                    'Error due saving data: ' . ($this->redis->getLastError() ?? ''),
                    500,
                    null
                );
            }
        } catch (RedisException $e) {
            throw new RedisConnectorException('Connector error', (int)$e->getCode(), $e);
        } catch (\JsonException $e) {
            throw new RedisConnectorException('Json encode error: ' . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function has($key): bool
    {
        return $this->redis->exists($key);
    }
}
