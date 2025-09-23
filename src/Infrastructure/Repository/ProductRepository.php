<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Exception;
use Raketa\BackendTestTask\Domain\Product;

class ProductRepository
{
    public function __construct(
        private Connection $connection
    ){
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function getByUuid(string $uuid): Product
    {
        $sql = "SELECT * FROM products WHERE uuid = :uuid";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('uuid', $uuid);

        $result = $stmt->executeQuery();
        $row = $result->fetchAssociative();

        if (empty($row)) {
            throw new Exception('Product not found');
        }

        return $this->createProductFromRow($row);
    }

    /**
     * @return Product[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function getActiveByCategory(string $category): array
    {
        $sql = "SELECT * FROM products WHERE category = :category AND is_active = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('category', $category);

        $result = $stmt->executeQuery();
        $rows = $result->fetchAllAssociative();

        return array_map(
            static fn (array $row): Product => $this->createProductFromRow($row),
            $rows
        );
    }

    public function createProductFromRow(array $row): Product
    {
        return new Product(
            (int) $row['id'],
            $row['uuid'],
            (bool) $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            (int)$row['price'],
        );
    }
}
