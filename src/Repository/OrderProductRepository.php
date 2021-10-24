<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderProduct[]    findAll()
 * @method OrderProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    /**
     * @param array $attributes
     * @return OrderProduct|null
     */
    public function create(array $attributes): ?OrderProduct
    {
        try {
            $orderProduct = new OrderProduct();
            $orderProduct->setOrderId($attributes['orderId']);
            $orderProduct->setQuantity($attributes['quantity']);
            $this->getEntityManager()->persist($orderProduct);
            $this->getEntityManager()->flush();
            return $orderProduct;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function insert(array $data): array
    {
        return array_map(function ($orderProduct) {
            return $this->create($orderProduct);
        }, $data);
    }

    /**
     * @param int $orderId
     * @param int $orderProductId
     * @param array $parameters
     * @return bool
     */
    public function update(int $orderProductId, array $parameters): bool
    {
        try {
            $orderProduct = $this->find($orderProductId);
            if (!is_null($orderProduct)) {
                if (isset($parameters['quantity'])) {
                    $orderProduct->setQuantity($parameters['quantity']);
                }
                if (isset($parameters['cratedAt'])) {
                    $orderProduct->setCreatedAt($parameters['createdAt']);
                }
                $this->getEntityManager()->flush();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
