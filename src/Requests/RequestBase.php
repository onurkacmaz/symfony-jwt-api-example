<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ValidatorBuilder;

abstract class RequestBase
{

    protected Request $request;
    protected ConstraintViolationListInterface $validation;

    /**
     * RequestBase constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validation = $this->validate();
    }

    /**
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    protected function getValidation(): ConstraintViolationListInterface
    {
        return $this->validation;
    }

    /**
     * @return array
     */
    public function all(): array {
        return $this->getRequest()->toArray();
    }

    /**
     * @return Collection
     */
    public function rules(): Collection {
        return new Collection();
    }

    /**
     * @return ConstraintViolationListInterface
     */
    protected function validate(): ConstraintViolationListInterface
    {
        $validatorBuilder = new ValidatorBuilder();
        return $validatorBuilder->getValidator()->validate($this->all(), $this->rules());
    }

    /**
     * @return bool
     */
    public function isValid(): bool {
        return !$this->getValidation()->count() > 0;
    }

    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function get($key, $default = null) {
        return $this->all()[$key] ?? $default;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool {
        return !is_null($this->get($key));
    }

    /**
     * @return array
     */
    public function errors(): array {
        $errors = [];
        foreach ($this->getValidation() as $constraint) {
            $errors[$constraint->getPropertyPath()][] = $constraint->getMessage();
        }
        return $errors;
    }

}