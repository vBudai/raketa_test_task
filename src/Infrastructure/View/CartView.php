<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\View;

use Raketa\BackendTestTask\Domain\Cart;

readonly class CartView
{
    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
            'customer' => [
                'id' => $cart->getCustomer()->getId(),
                'name' => implode(' ', [
                    $cart->getCustomer()->getLastName(),
                    $cart->getCustomer()->getFirstName(),
                    $cart->getCustomer()->getMiddleName(),
                ]),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'payment_method' => $cart->getPaymentMethod(),
            'total' => $cart->calculateTotal(),
        ];

        $data['items'] = [];
        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $product->getPrice(),
                'quantity' => $item->getQuantity(),
                'product'=> [
                    'id' => $product->getId(),
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
            ];
        }

        return $data;
    }
}
