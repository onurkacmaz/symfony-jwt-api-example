<?php

namespace App\Repository;

use App\Entity\OrderAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method OrderAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderAddress[]    findAll()
 * @method OrderAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderAddress::class);
    }

    /**
     * @param array $attributes
     * @return OrderAddress|null
     */
    public function create(array $attributes): ?OrderAddress {
        try {
            $orderAddress = new OrderAddress();
            $orderAddress->setOrderId($attributes['orderId']);
            $orderAddress->setAddress($attributes['address']);
            $orderAddress->setCity($attributes['city']);
            $orderAddress->setDistrict($attributes['district']);
            $orderAddress->setCountry($attributes['country']);
            $this->getEntityManager()->persist($orderAddress);
            $this->getEntityManager()->flush();
            return $orderAddress;
        }catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param int $orderId
     * @param array $attributes
     * @return bool
     */
    public function update(int $orderId, array $attributes): bool {
        try {
            $orderAddress = $this->findBy(['order_id' => $orderId])[0];
            if (isset($attributes['address'])) {
                $orderAddress->setAddress($attributes['address']);
            }
            if (isset($attributes['city'])) {
                $orderAddress->setCity($attributes['city']);
            }
            if (isset($attributes['district'])) {
                $orderAddress->setDistrict($attributes['district']);
            }
            if (isset($attributes['country'])) {
                $orderAddress->setCountry($attributes['country']);
            }
            if (isset($attributes['createdAt'])) {
                $orderAddress->setCreatedAt($attributes['createdAt']);
            }
            $this->getEntityManager()->flush();
            return true;
        }catch (Exception $e) {
            return false;
        }
    }

}
