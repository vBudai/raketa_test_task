<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class Cart implements \JsonSerializable
{
    public function __construct(
        private readonly string $uuid,
        private readonly  Customer $customer,
        private readonly  string $paymentMethod,
        /** @var CartItem[] */
        private array $items,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function calculateTotal(): int
    {
        return array_reduce($this->items, static fn($sum, $item) => $sum + $item->getProduct()->getPrice() * $item->getQuantity(), 0);
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'customer' => $this->customer->jsonSerialize(),
            'payment_method' => $this->paymentMethod,
            'items' => array_map(static fn(CartItem $it) => $it->jsonSerialize(), $this->items),
        ];
    }

    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ($data['items'] ?? [] as $item) {
            if (is_array($item)) {
                $items[] = CartItem::fromArray($item);
            }
        }

        return new self(
            (string)($data['uuid'] ?? ''),
            Customer::fromArray($data['customer'] ?? []),
            (string)($data['payment_method'] ?? 'not-selected'),
            $items
        );
    }
}
