<?php
namespace App\Dao;

class ProductDao extends BaseDao {

    public function filteredProduct(iterable $params) {
        $sql = "SELECT * FROM product WHERE product.price < :price ORDER BY product.id ASC";
        return $this->doQuery($sql, $params);  // $result
    }

    public function productInsert(iterable $values=[]){
        $sql = "INSERT INTO product SET
                name  = :pname, 
                price = :pprice
                ";
        return $this->doSQL($sql, $values);
    }
}