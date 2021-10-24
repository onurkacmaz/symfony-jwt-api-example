<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

class OrderUpdateRequest extends RequestBase
{
    public function rules(): Collection {
        return new Collection([
            'orderCode' => [
                new Type('string'),
                new NotBlank()
            ],
            'products' => new Optional([
                new Type('array'),
                new NotBlank(),
                new All([
                    new Collection([
                        'quantity' => new Optional([
                            new Type('int'),
                            new NotBlank(),
                            new GreaterThan(0)
                        ]),
                        'productId' => [
                            new Type('int'),
                            new NotBlank(),
                            new GreaterThan(0)
                        ],
                    ])
                ])
            ]),
            'address' => new Optional([
                new Type('array'),
                new NotBlank(),
                new Collection([
                    'address' => new Optional([
                        new Type('string'),
                        new NotBlank()
                    ]),
                    'city' => new Optional([
                        new Type('string'),
                        new NotBlank()
                    ]),
                    'district' => new Optional([
                        new Type('string'),
                        new NotBlank()
                    ]),
                    'country' => new Optional([
                        new Type('string'),
                        new NotBlank()
                    ]),
                ])
            ]),
            'shippingDate' => new Optional(),
            'createdAt' => new Optional()
        ]);
    }
}