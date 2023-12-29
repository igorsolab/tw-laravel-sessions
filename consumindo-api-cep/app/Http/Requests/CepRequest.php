<?php

namespace App\Http\Requests;

use App\Rules\CepRule;
use App\Services\CepServices;
use Illuminate\Foundation\Http\FormRequest;

class CepRequest extends FormRequest
{
    public $cepServices;
    public function __construct(CepServices $cepServices)
    {   
        $this->cepServices = $cepServices;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cep'=>['required', new CepRule($this->cepServices)]
        ];
    }
}
