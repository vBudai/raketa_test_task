<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

use Ramsey\Uuid\Uuid;

final readonly class Product implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private string $uuid,
        private bool $isActive,
        private string $category,
        private string $name,
        private string $description,
        private string $thumbnail,
        private int $price,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'isActive' => $this->isActive,
            'category' => $this->category,
            'name' => $this->name,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int)($data['id'] ?? 0),
            (string)($data['uuid'] ?? Uuid::uuid4()->toString()),
            (bool)($data['isActive'] ?? false),
            (string)($data['category'] ?? ''),
            (string)($data['name'] ?? ''),
            (string)($data['description'] ?? ''),
            (string)($data['thumbnail'] ?? ''),
            (int)($data['price'] ?? 0)
        );
    }
}
