<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

use Ramsey\Uuid\Uuid;

final class CartItem implements \JsonSerializable
{
    public function __construct(
        private readonly string $uuid,
        private readonly int $quantity,
        private readonly string $productUuid,
        private ?Product $product = null,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): ?Product
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
            'productUuid' => $this->productUuid,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['uuid'] ?? Uuid::uuid4()->toString(),
            (int)($data['quantity'] ?? 0),
            (string)($data['productUuid'] ?? ''),
        );
    }
}
