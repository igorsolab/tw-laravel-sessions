<?php

namespace App\Rules;

use App\Services\CepServices;
use Illuminate\Contracts\Validation\Rule;

class CepRule implements Rule
{
    public $cepServices;
    public function __construct(CepServices $cepServices)
    {   
        $this->cepServices = $cepServices;
    }
    /**
     * Run the validation rule.
     *@param string $attribute
     *@param string $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->cepServices->validar($value);
        // return false;
    }
    public function message()
    {
        return "O CEP passado é invalido";
    }
}
