<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Calculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/api/{country_code}/product/{id}", name="products", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("country", options={"mapping": {"country_code": "code"}})
     */
    public function getPriceByCountry(Country $country = null, Product $product = null)
    {

        if( $country === null ){
            throw $this->createNotFoundException('Country code is invalid');
        }

        if( $product === null ){
            throw $this->createNotFoundException('Product id is invalid');
        }

        $rate = $country->getCurrency()->getRate();
        $defaultPrice = $product->getDefaultPrice();
        $price = round($this->calculator->calculate($defaultPrice, $rate), 2);

        return $this->json(
            [
                'status' => 'success',
                'data' => [
                    'product' => [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'description' => $product->getDescription(),
                        'defaultPrice' => [
                            'amount' => $product->getDefaultPrice(),
                            'defaultCurrency' => [
                                'name' => $product->getDefaultCurrency()->getName(),
                                'code' => $product->getDefaultCurrency()->getCode()
                            ]                            
                        ],
                    ],
                    'calculated_price' => [
                        'amount' => $price,
                        'currency' => [
                            'name' => $country->getCurrency()->getName(),
                            'code' => $country->getCurrency()->getCode()
                        ]
                    ]
                ]
            ]
        );
    }
}
