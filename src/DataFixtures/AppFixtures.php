<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Customer;
use App\Entity\ShoppingCart;
use App\Entity\Taxation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class AppFixtures extends Fixture
{
    private const FIRST_NAME_POOL = [
        'Adam', 'Beatrix', 'Celia', 'Donald', 'Edith', 'Ferdinand', 'Gaben', 'Helena', 'Max'
    ];

    private const LAST_NAME_POOL = [
        'Smith', 'Maier', 'Müller', 'van Schulz', 'Mustermann'
    ];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $customer = new Customer();
            $customer->setFirstName(self::FIRST_NAME_POOL[array_rand(self::FIRST_NAME_POOL)]);
            $customer->setLastName(self::LAST_NAME_POOL[array_rand(self::LAST_NAME_POOL)]);
            $shoppingCart = new ShoppingCart();
            $customer->setShoppingCart($shoppingCart);
            $manager->persist($shoppingCart);

            $article = new Article();
            $article->setArticleNumber(ByteString::fromRandom(8)->toString());
            $article->setDescription(ByteString::fromRandom(300)->toString());
            $article->setBasePrice(random_int(1, 100000000));
            
            $manager->persist($article);
            $manager->persist($customer);
        }

        $higherTax = new Taxation();
        $higherTax->setTaxName("19% Mehrwertsteuer - DE");
        $higherTax->setTaxValuePercentage(19.00);

        $lowerTax = new Taxation();
        $lowerTax->setTaxName("7% Mehrwertsteuer - DE");
        $lowerTax->setTaxValuePercentage(7.00);

        $manager->flush();
    }
}
