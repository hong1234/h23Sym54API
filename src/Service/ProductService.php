<?php
namespace App\Service;

use App\Dao\ProductDao;

class ProductService
{
    private $pDao;

    public function __construct(ProductDao $pDao) {
        $this->pDao = $pDao; 
    } 

    public function getProductByMax($max){
        $params = [
            'price' => $max
        ];

        $result = $this->pDao->filteredProduct($params);
        return $result->fetchAllAssociative();
    }
}