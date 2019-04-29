<?php
/**
 * Created by PhpStorm.
 * User: agutierrez
 * Date: 2019-02-21
 * Time: 20:15
 */

declare(strict_types=1);

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

final class WakupRequestsTest extends TestCase
{

    private static function getClient() : \Wakup\Client
    {
        return new Wakup\Client();
    }

    public function testGetWakupPaginatedAttributesValue() : void
    {
        $this->assertInstanceOf(
            Wakup\PaginatedAttributes::class,
            static::getClient()->getPaginatedAttributes()
        );
    }

    public function testGetWakupPaginatedCategoriesValue() : void
    {
        $this->assertInstanceOf(
            Wakup\PaginatedCategories::class,
            static::getClient()->getPaginatedCategories()
        );
    }

    public function testGetWakupPaginatedProductsValue() : void
    {
        $pagination = static::getClient()->getPaginatedProducts(null, 0, 100);
        $this->assertInstanceOf(\Wakup\PaginatedProducts::class, $pagination);
        foreach ($pagination->getProducts() as $product) {
            $this->assertInstanceOf(\Wakup\Product::class, $product);
            $this->assertIsString($product->getSku());
            $info = $product->getInfo();
            if ($info != null) {
                $this->assertInstanceOf(\Wakup\ProductInfo::class, $info);
                $this->assertIsString($info->getName());
                #$this->assertIsString($info->getDescription());
                $this->assertIsString($info->getShortDescription());
                $this->assertIsArray($info->getShortDescriptionItems());
                $this->assertIsString($info->getCategory());
                $this->assertIsArray($info->getRelatedProducts());
                $this->assertIsArray($info->getRequiredProducts());
                $this->assertIsBool($info->isActive());
                $this->assertIsBool($info->hasWarrantyPlans());
                $this->assertIsBool($info->isVisibleIndividually());
            }
        }
    }

    public function testGetWakupNearestStoresValue() : void
    {
        $pagination = static::getClient()->getNearestStores(9, -82, 0, 10);
        $this->assertInstanceOf(\Wakup\PaginatedStores::class, $pagination);
        $lastDistance = 0;
        foreach ($pagination->getStores() as $store) {
            $this->assertInstanceOf(\Wakup\Store::class, $store);
            $this->assertIsString($store->getSku());
            $this->assertIsString($store->getName());
            $this->assertIsString($store->getAddress());
            $this->assertIsFloat($store->getLatitude());
            $this->assertIsFloat($store->getLongitude());
            $this->assertIsFloat($store->getDistanceInKms());
            $this->assertIsFloat($store->getDistanceInMiles());
            $this->assertIsInt($store->getShipmentTime());
            # Warehouse ID should be empty at this point
            $this->assertNull($store->getWarehouseId());
            # Should be ordered by distance
            $this->assertGreaterThanOrEqual($lastDistance, $store->getDistanceInMiles());
            $lastDistance = $store->getDistanceInMiles();
        }
    }

}