<?php

namespace App\Http\Controllers;

use App\Repositories\CursoRepository;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public CursoRepository $cursoRepository;

    public function __construct(CursoRepository $cursoRepository)
    {
        $this->cursoRepository = $cursoRepository;
    }
    public function list()
    {
        $cursos = $this->cursoRepository->list();
        dd($cursos);
    }
    public function show()
    {
        $cursos = $this->cursoRepository->show('x6pxz8dOWeaKt0hs');
        dd($cursos);
    }
    public function create()
    {
        $resposta = $this->cursoRepository->create([
            'nome'=>"PHP Intermediário",
            'linguagem'=>'PHP'
        ]);
        if($resposta)
        {
            return 'Criado com sucesso';
        }
        return 'Erro ao criar';
    }
    public function update()
    {
        $resposta = $this->cursoRepository->update('NsmsqcMTCUye1Tb4',[
            'nome'=>"PHP Intermediário Com Orientação a Objetos",
            'linguagem'=>'PHP'
        ]);
        if($resposta)
        {
            return 'Atualizado com sucesso com sucesso';
        }
        return 'Erro ao atualizar';
    }
    public function destroy()
    {
        $resposta = $this->cursoRepository->destroy('QTtvZY9iBq80ooBD');

        if($resposta)
        {
            return 'Deletado com sucesso';
        }
        return 'Erro ao deletar';
    }
}
