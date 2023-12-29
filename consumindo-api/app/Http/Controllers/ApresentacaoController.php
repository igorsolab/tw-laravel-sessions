<?php  

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class ApresentacaoController
{
    public function olaMundo()
    {
        $pdf = Pdf::loadHTML('<h1>Teste</h1>');
        return $pdf->stream();
    }
    public function cursos()
    {
        $cursos = [
            'php' => [
                'nome' => "Curso PHP",
                'versao' => 8
            ],
            'java' => [
                'nome' => "Curso Java",
                'versao' => 12
            ]
        ];
        $dompdf = Pdf::loadView('cursos',compact('cursos'));
        return $dompdf->stream();
    }
}