<?php

namespace App\Http\Resources;

use App\Services\LinksGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlunoUnico extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $links = new LinksGenerator;
        $links->get( route('alunos.show', $this->id),'alunos_detalhes');
        $links->put( route('alunos.update', $this->id),'alunos_atualizar');
        $links->delete( route('alunos.destroy', $this->id),'alunos_remover');
        return [
            'nome_aluno'=>$this->nome,
            'turma'=> new TurmaResource($this->whenLoaded('turma')),
            // 'turma'=> new TurmaResource($this->turma)
            'links'=>$links->toArray()
        ];
    }
}
