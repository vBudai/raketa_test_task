<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

use Ramsey\Uuid\Uuid;

final class CartItem implements \JsonSerializable
{
    public function __construct(
        private readonly string $uuid,
        private Product $product,
        private readonly int $quantity,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'product' => $this->product->jsonSerialize(),
            'quantity' => $this->quantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['uuid'] ?? Uuid::uuid4()->toString(),
            Product::fromArray($data['product'] ?? []),
            (int)($data['quantity'] ?? 0)
        );
    }
}
