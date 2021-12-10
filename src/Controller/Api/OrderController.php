<?php

namespace App\Controller\Api;

use App\Repository\OrderAddressRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Requests\OrderStoreRequest;
use App\Requests\OrderUpdateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{

    /**
     * @param int $orderId
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param OrderAddressRepository $orderAddressRepository
     * @param OrderProductRepository $orderProductRepository
     * @return JsonResponse
     */
    public function update(int $orderId, Request $request, OrderRepository $orderRepository, OrderAddressRepository $orderAddressRepository, OrderProductRepository $orderProductRepository): JsonResponse
    {
        $request = new OrderUpdateRequest($request);
        $requestAsArray = $request->all();

        if (!$request->isValid()) {
            return $this->json($request->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (is_null($orderRepository->find($orderId))){
            return $this->json([
                'message' => 'Order not found.'
            ], 404);
        }

        if (!is_null($orderRepository->find($orderId)->getShippingDate())) {
            return $this->json([
                'message' => 'This order shipped bc. you can not update this order.'
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($orderRepository->isOrderCodeUsed($this->getUser()->getId(), $request->get('orderCode'), $orderId)) {
            return $this->json([
                'message' => 'orderCode already used by another order.'
            ], 404);
        }

        $orderRepository->update($orderId, $requestAsArray);
        if ($request->has('address')) {
            $orderAddressRepository->update($orderId, $requestAsArray['address']);
        }
        if ($request->has('products')) {
            array_map(static function ($orderProduct) use ($orderProductRepository) {
                $orderProductRepository->update($orderProduct['productId'], $orderProduct);
            }, $requestAsArray['products']);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param OrderAddressRepository $orderAddressRepository
     * @param OrderProductRepository $orderProductRepository
     * @return JsonResponse
     */
    public function store(Request $request, OrderRepository $orderRepository, OrderAddressRepository $orderAddressRepository, OrderProductRepository $orderProductRepository): JsonResponse
	{
        $request = new OrderStoreRequest($request);
        $requestAsArray = $request->all();
        if (!$request->isValid()) {
            return $this->json($request->errors(), Response::HTTP_BAD_REQUEST);
        }

        if ($orderRepository->isOrderCodeUsed($this->getUser()->getId(), $request->get('orderCode'))) {
            return $this->json([
                'message' => 'orderCode already used by another order.'
            ], Response::HTTP_BAD_REQUEST);
        }
        $order = $orderRepository->create(
            array_merge(
                $request->all(),
                [
                    'userId' => $this->getUser()->getId()
                ]
            )
        );
        $orderAddress = $orderAddressRepository->create(
            array_merge(
                $requestAsArray['address'],
                [
                    'orderId' => $order->getId()
                ]
            )
        );
        $orderProducts = $orderProductRepository->insert(
            array_map(static function ($orderProduct) use ($order) {
                return array_merge(
                    $orderProduct,
                    [
                        'orderId' => $order->getId()
                    ]
                );
            }, $requestAsArray['products']),
        );
        return $this->json([
            'data' => [
                'order' => $order,
                'orderAddress' => $orderAddress,
                'orderProducts' => $orderProducts
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * @param int $orderId
     * @param OrderRepository $orderRepository
     * @return JsonResponse
     */
    public function show(int $orderId, OrderRepository $orderRepository): JsonResponse
    {
        return $this->json([
            'data' => $orderRepository->findBy(['id' => $orderId, 'userId' => $this->getUser()->getId()])
        ], Response::HTTP_OK);
    }

    /**
     * @param OrderRepository $orderRepository
     * @return JsonResponse
     */
    public function index(OrderRepository $orderRepository): JsonResponse
    {
        return $this->json([
            'data' => $orderRepository->findBy(['userId' => $this->getUser()->getId()])
        ], Response::HTTP_OK);
    }
}
