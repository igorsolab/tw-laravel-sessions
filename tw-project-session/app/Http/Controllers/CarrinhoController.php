<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    public function listar(Request $request)
    {
        // $produto = $request->session()->get('produto');
        // var_dump($produto);
        // $produto = session("produto",'Produto não encontrado');
        // $total = session("total",'R$ 0');
        // var_dump($produto, $total);
        dd(session()->all());
    }
    public function adicionar(Request $request)
    {
        // $request->session()->put("produto",'Bicicleta');
        // return 'adicionado com sucesso';
        // session(["produto" => "bola", "total"=>"R$ 123,00"]);

        if($request->session()->missing('produtos')){
            $request->session()->put('produtos',[]);
        }
        $request->session()->push('produtos',$request->produto);
        return 'adicionado com sucesso';

        // OS arquivos ficam salvos na pasta ./storage/framework/sessions
    }
    public function remover(Request $request)
    {
        // $request->session()->forget('produto');
        // $request->session()->flush();
        if($request->session()->has('produtos')){
            session()->forget(['produtos']);
            // session()->forget(['produto','total']);
            return "Removido com sucesso";
        }
        return "Não há produtos para remover";

        // Metodos LARAVEL para verificar valores das sessões
        // session()->exists('') --> Ele verifica se existe o campo, ou seja, as vezes excluimos o campo e não os valores, ele verifica a existencia do campo
        // session()->missing('') --> ele verifica se está faltando o campo escolhido
    }
}
