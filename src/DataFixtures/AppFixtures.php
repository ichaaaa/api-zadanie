<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER],            
        ],
        [
            'username' => 'super_admin',
            'email' => 'admin@typer.com',
            'password' => 'Niuniek@123',
            'fullName' => 'Typer Admin',
            'roles' => [User::ROLE_ADMIN],            
        ],
    ];	

	private const CURRENCY = [
        [
            'name' => 'Polish zloty',
            'code' => 'PLN',
            'rate' => '1'
        ],
        [
            'name' => 'Australian Dollar',
            'code' => 'AUD',
            'rate' => '2.7223'
        ],
        [
            'name' => 'Czech Koruna',
            'code' => 'CZK',
            'rate' => '0.1666'
        ],
        [
            'name' => 'Euro',
            'code' => 'EUR',
            'rate' => '4.2790'
        ],
        [
            'name' => 'Pound Sterling',
            'code' => 'GBP',
            'rate' => '4.9483'
        ],
        [
            'name' => 'Russian Ruble',
            'code' => 'RUB',
            'rate' => '0.0593'
        ],
        [
            'name' => 'US Dollar',
            'code' => 'USD',
            'rate' => '3.8002'
        ],
    ];

    public const COUNTRY = [
    	[
    		'name' => 'Poland',
    		'code' => 'POL'
    	],
    	[
    		'name' => 'Australia',
    		'code' => 'AUS'
    	],
    	[
    		'name' => 'Czech Republic',
    		'code' => 'CZE'
    	],
    	[
    		'name' => 'Italy',
    		'code' => 'ITA'
    	],
    	[
    		'name' => 'Finland',
    		'code' => 'FIN'
    	],
    	[
    		'name' => 'United Kingdom',
    		'code' => 'GBR'
    	],
    	[
    		'name' => 'Russian Federation',
    		'code' => 'RUS'
    	],
    	[
    		'name' => 'United States of America',
    		'code' => 'USA'
    	],
    ];

    public const PRODUCT = [
    	[
    		'name' => 'Snowboard Board',
    		'description' => 'Snowboard Board for snowboarding',
    		'price' => '100'
    	],
    	[
    		'name' => 'Skis',
    		'description' => 'Skis for skiing',
    		'price' => '120'
    	],
    	[
    		'name' => 'Helmet',
    		'description' => 'Helmet for safety',
    		'price' => '80'
    	],
    	[
    		'name' => 'Snowboard Boots',
    		'description' => 'Snowboard Boots for snowboarding',
    		'price' => '90'
    	],
    	[
    		'name' => 'Goggles',
    		'description' => 'Goggles to see better',
    		'price' => '90'
    	],
    ];

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

    public function load(ObjectManager $manager)
    {
    	$this->loadUsers($manager);
    	$this->loadCurrency($manager);
    	$this->loadProduct($manager);
    	$this->loadCountry($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullname($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);

            $manager->persist($user);
        }
        $manager->flush();
    }


    public function loadCurrency(ObjectManager $manager)
    {
    	$i = 0;
    	foreach (self::CURRENCY as $currencyData) {
    		$currency = new Currency();
    		$currency->setName($currencyData['name']);
    		$currency->setCode($currencyData['code']);
    		$currency->setRate($currencyData['rate']);
    		$this->addReference($currencyData['code'], $currency);

			$manager->persist($currency);


    	}
		$manager->flush();
    }

    public function loadProduct(ObjectManager $manager)
    {
    	foreach(self::PRODUCT as $productData){
    		$product = new Product();
    		$product->setName($productData['name']);
    		$product->setDescription($productData['description']);
    		$product->setDefaultCurrency($this->getReference(self::CURRENCY[0]['code']));
    		$product->setDefaultPrice($productData['price']);

    		$manager->persist($product);
    	}
    	$manager->flush();
    }

    public function loadCountry(ObjectManager $manager)
    {
    	$i = 0;
    	foreach(self::COUNTRY as $countryData){
    		$country = new Country();
 			if((self::CURRENCY[$i]['code'] == 'EUR') && ($countryData['code'] == 'FIN' || $countryData['code'] == 'ITA')) {
 				$country->setName($countryData['name']);
 				$country->setCode($countryData['code']);
 				$country->setCurrency($this->getReference('EUR'));
				$manager->persist($country);
				if($countryData['code'] == 'FIN'){
					$i++;
				}
 				continue;
 			}else{
 				$country->setName($countryData['name']);
 				$country->setCode($countryData['code']);
 				$country->setCurrency($this->getReference(self::CURRENCY[$i]['code']));
 				$i++;
 			}
 			$manager->persist($country);
    	}
    	$manager->flush();  
    }
}
