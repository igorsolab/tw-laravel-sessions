<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlunoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nome_aluno'=>$this->nome,
            'nascimento'=>$this->nascimento,
            'genero'=>$this->genero,
            'turma'=>$this->turma_id
        ];
    }
}
