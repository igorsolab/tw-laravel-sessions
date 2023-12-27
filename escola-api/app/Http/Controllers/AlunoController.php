<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoRequest;
use App\Http\Resources\AlunoColecao;
use App\Http\Resources\AlunoCollection;
use App\Http\Resources\AlunoResource;
use App\Http\Resources\AlunoUnico;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlunoController extends Controller
{
    /**
     *  @OA\Get(
     *      path="/api/alunos",
     *      summary="Lista os alunos cadastrados",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     *  )
     * 
     * Display a listing of the resource.
     * @return AlunoCollection
     */
    public function index(Request $request):AlunoColecao
    {
        // return $response->setContent('{"Teste":"Validado"}')
        // ->setStatusCode(404)
        // ->header('Content-type','application/json');


        // return response('teste',400);

        // return Aluno::get()->makeHidden('turma_id');


        // return new AlunoColecao(
        //     Aluno::with('turma')->get());

        if($request->query('relacao') === 'turma'){
            $alunos = Aluno::with('turma')->paginate(2);
        }
        else{
            $alunos = Aluno::get();
        }
        return new AlunoColecao($alunos);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(AlunoRequest $request)
    {
        return response(Aluno::create($request->all()),201);
    }

    /**
     * @OA\Get(
     *      path="/api/alunos/{id}",
     *      summary="Detalhe de aluno cadastrado",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     * 
     * 
     * Display the specified resource.
     */
    public function show(Aluno $aluno)
    {
        if(request()->header("Accept") == "application/xml")
        {
            return $this->pegarAlunoXmlResponse($aluno);
        }
        if(request()->wantsJson()) {
            return new AlunoUnico($aluno);
        }
        else{
            return "Formato indisponível";
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(AlunoRequest $request, Aluno $aluno)
    {
        $aluno->update($request->all());
        return $aluno;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aluno $aluno)
    {
        return response($aluno->delete(),200);
    }

    private function pegarAlunoXmlResponse(Aluno $aluno): Response
    {
        $aluno = $aluno->toArray();

        $xml = new \SimpleXMLElement('<aluno/>');

        array_walk_recursive($aluno,function($valor,$chave) use($xml){
            $xml->addChild($chave,$valor);
        });
        return response($xml->asXML())->header('Content-Type','application/xml');
    }
}
