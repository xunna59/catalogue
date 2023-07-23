<?php

interface ProductSorterInterface
{
    public function sort(array $products): array;
}

class ProductPriceSorter implements ProductSorterInterface
{
    public function sort(array $products): array
    {
        usort($products, function ($product1, $product2) {
            return $product1['price'] <=> $product2['price'];
        });

        return $products;
    }
}

class ProductSalesPerViewSorter implements ProductSorterInterface
{
    public function sort(array $products): array
    {
        usort($products, function ($product1, $product2) {
            $salesPerView1 = $product1['sales_count'] / $product1['views_count'];
            $salesPerView2 = $product2['sales_count'] / $product2['views_count'];

            return $salesPerView2 <=> $salesPerView1;
        });

        return $products;
    }
}

class Catalog
{
    private $products;
    private $sorter;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function setSorter(ProductSorterInterface $sorter)
    {
        $this->sorter = $sorter;
    }

    public function getSortedProducts(): array
    {
        if ($this->sorter === null) {
            throw new Exception("Sorter is not set.");
        }

        return $this->sorter->sort($this->products);
    }
}

// Sample products array
$products = [
    [
        'id' => 1,
        'name' => 'Alabaster Table',
        'price' => 12.99,
        'created' => '2019-01-04',
        'sales_count' => 32,
        'views_count' => 730,
    ],
    [
        'id' => 2,
        'name' => 'Zebra Table',
        'price' => 44.49,
        'created' => '2012-01-04',
        'sales_count' => 301,
        'views_count' => 3279,
    ],
    [
        'id' => 3,
        'name' => 'Coffee Table',
        'price' => 10.00,
        'created' => '2014-05-28',
        'sales_count' => 1048,
        'views_count' => 20123,
    ],
];

// Create the catalog
$catalog = new Catalog($products);

// Set the sorter for sorting by price
$catalog->setSorter(new ProductPriceSorter());
$productsSortedByPrice = $catalog->getSortedProducts();

// Set the sorter for sorting by sales per view
$catalog->setSorter(new ProductSalesPerViewSorter());
$productsSortedBySalesPerView = $catalog->getSortedProducts();

// Displaying the sorted products
print_r($productsSortedByPrice);
print_r($productsSortedBySalesPerView);