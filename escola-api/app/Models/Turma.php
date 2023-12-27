<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alunos():\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Aluno::class);
    }
}
