<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    /**
     * Indica os campos que podem ter valor definidos via definição de dados em massa
     */
    protected $fillable = ['nome','nascimento','genero','turma_id'];



    /**
     * Defini as copnversões no momento da serialização
     */
    // protected $casts = [
    //     'nascimento' => 'date:d/m/Y'
    // ];

    /**
     * Não retorna campos na requisição na serialização
     */
    // protected $hidden = ['created_at','updated_at'];

    /**
     * Tornar campos visiveis no retorno da requisição
     */
    // protected $visible = ['id','nome','genero','nascimento','turma_id','aceito'];

    // protected $appends = ['aceito'];
 
    /**
     * Defini a relação com a tabela turma (Cada aluno contém uma turma)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function turma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }


    // public function getAceitoAttribute()
    // {
    //     return $this->attributes['nascimento'] > '2001-01-01' ? 'aceito' : "Não aceito";
    // }
}
