<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;

use App\Dao\ProductDao;
use App\Service\ProductService;

/**
 * Class ProductController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class ProductController // extends AbstractController
{
    private $pDao;
    private $pService;
    
    public function __construct(ProductDao $pDao, ProductService $pService)
    {
        $this->pDao = $pDao;
        $this->pService = $pService;
    }

    /**
    * @Route("/product", name="product_insert")
    */
    public function productInsert(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard4');
        $product->setPrice(4000);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
    * @Route("/product/insert", name="product_insert2", methods={"POST"})
    */
    public function productInsertNr2(Request $request): Response
    {
        $error = '';
        $rs = [];

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true); // array()

            if($data != null){
                
                // validation
                // $error = $validator->isValidMaklerInput2($myfactory_interessent_id, $data); 

                if ($error == '') {

                    $rowCount = $this->pDao->productInsert([
                        'pname'  => $data['pname'],
                        'pprice' => $data['pprice']
                    ]);

                    $rs["status"] = [
                        "statuscode" => "200", 
                        "message" => "Product erfolgreich angelegt",
                        "error" => $error
                    ];

                    $rs["data"] = $rowCount; // $data;

                } else {
                    $rs["status"] = [
                        "statuscode" => "401", 
                        "message" => "Product kann nicht angelegt werden",
                        "error" => $error
                    ];
                }

            } else {
                
                $rs["status"] = [
                    "statuscode" => "401", 
                    "message" => "Product kann nicht angelegt werden",
                    "error" => "--Input-Parameters sind json-invalid"
                ];
            }
        }
        
        return new Response(json_encode($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
    * @Route("/product/filter/{max}", name="product_filter", methods={"GET"}, requirements={"max"="\d+"})
    */
    public function productFilter(ProductDao $pDao, int $max): Response
    {  
        $params = [
            'price' => $max
        ];

        $result = $pDao->filteredProduct($params);
        $result_array = $result->fetchAllAssociative();

        // $result_array = [];
        // while ($row = $result->fetchAssociative()){
        //     $result_array[] = $row;
        // }

        return new Response(json_encode($result_array), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
    * @Route("/product/filter2/{max}", name="product_filter2", methods={"GET"}, requirements={"max"="\d+"})
    */
    public function productFilterNr2(int $max, ProductService $pService): Response
    {  
        $result_array = $pService->getProductByMax($max);
        return new Response(json_encode($result_array), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
    * @Route("/product/filter3/{max}", name="product_filter3", methods={"GET"}, requirements={"max"="\d+"})
    */
    public function productFilterNr3(int $max): Response
    {  
        $result_array = $this->pService->getProductByMax($max);
        return new Response(json_encode($result_array), Response::HTTP_OK, ['Content-Type' => 'application/json']);
        // return $this->json($result_array);
    }
    
}