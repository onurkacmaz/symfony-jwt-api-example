<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

class OrderStoreRequest extends RequestBase
{

    public function rules(): Collection {
        return new Collection([
            'orderCode' => [
                new Type('string'),
                new NotBlank()
            ],
            'products' => [
                new Type('array'),
                new NotBlank(),
                new All([
                    new Collection([
                        'quantity' => [
                            new Type('int'),
                            new NotBlank(),
                            new GreaterThan(0)
                        ]
                    ])
                ])
            ],
            'address' => [
                new Type('array'),
                new NotBlank(),
                new Collection([
                    'address' => [
                        new Type('string'),
                        new NotBlank()
                    ],
                    'city' => [
                        new Type('string'),
                        new NotBlank()
                    ],
                    'district' => [
                        new Type('string'),
                        new NotBlank()
                    ],
                    'country' => [
                        new Type('string'),
                        new NotBlank()
                    ],
                ])
            ],
            'shippingDate' => new Optional(),
            'createdAt' => new Optional()
        ]);
    }

}