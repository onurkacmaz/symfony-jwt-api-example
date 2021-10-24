<?php

namespace App\Repository;

use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param array $attributes
     * @return Order|null
     */
    public function create(array $attributes): ?Order
    {
        try {
            $order = new Order();
            $order->setUserId($attributes['userId']);
            $order->setOrderCode($attributes['orderCode']);
            $order->setShippingDate(new DateTime($attributes['shippingDate'] ?? null));
            $order->setCreatedAt(new DateTime($attributes['createdAt'] ?? null));
            $this->getEntityManager()->persist($order);
            $this->getEntityManager()->flush();
            return $order;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param int $orderId
     * @param array $attributes
     * @return bool
     */
    public function update(int $orderId, array $attributes): bool
    {
        try {
            $order = $this->find($orderId);
            if (isset($attributes['orderCode'])) {
                $order->setOrderCode($attributes['orderCode']);
            }
            if (isset($attributes['userId'])) {
                $order->setUserId($attributes['userId']);
            }
            if (isset($attributes['shippingDate'])) {
                $order->setShippingDate(new DateTime($attributes['shippingDate']));
            }
            if (isset($attributes['createdAt'])) {
                $order->setCreatedAt(new DateTime($attributes['createdAt']));
            }
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int $orderId
     * @param int $userId
     * @param string $orderCode
     * @return bool
     */
    public function isOrderCodeUsed(int $userId, string $orderCode, int $orderId = null): bool
    {
        $orders = $this->createQueryBuilder('orders')
            ->andWhere('orders.orderCode = :orderCode')
            ->andWhere('orders.userId = :userId')
            ->setParameter('userId', $userId)
            ->setParameter('orderCode', $orderCode);
        if (!is_null($orderId)) {
            $orders = $orders->andWhere('orders.id != :orderId')->setParameter('orderId', $orderId);
        }

        $orders = $orders->getQuery()->execute();
        return count($orders) > 0;
    }

}
