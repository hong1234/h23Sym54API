<?php
namespace App\Dao;

use Doctrine\DBAL\Connection;
// use Doctrine\ORM\EntityManagerInterface;

class BaseDao { 

    private $connection;

    // function __construct(EntityManagerInterface $em) {
    //     $this->connection = $em->getConnection();  // Doctrine\DBAL\Connection
    // }

    function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function doQuery($sql, $values=[]){
        // https://github.com/doctrine/dbal/issues/4607
        
        // old code
        // $sql = 'SELECT * FROM user';
        // $result = $connection->prepare($sql)->execute();

        // $result->fetch();
	    // $result->fetchAll();

        // new code
        // $sql = 'SELECT * FROM user';
        // $result = $connection->prepare($sql)->executeQuery();

        //---------------------------------
        $stmt = $this->connection->prepare($sql);  // Doctrine\DBAL\Statement 
	    $result = $stmt->executeQuery($values);    // Doctrine\DBAL\Result
        return $result;

        // $result->fetchAllAssociative();
        // $result->fetchAssociative();

        // $result_array = [];
        // while ($row = $result->fetchAssociative()){
        //     $result_array[] = $row;
        // }
        // return $result_array;
    }

    public function doSQL($sql, $values=[]){

        // old code
        // $sql = 'UPDATE user SET disabled = 1';
        // $result = $connection->prepare($sql)->execute();

        // new code
        // $sql = 'UPDATE user SET disabled = 1';
        // $result = $connection->prepare($sql)->executeStatement();

        //--------------------------
        $stmt = $this->connection->prepare($sql);
        $rowCount = $stmt->executeStatement($values); // returns the affected rows count
        return $rowCount;
    }

    public function dbConnection(){
        return $this->connection;
    }

}