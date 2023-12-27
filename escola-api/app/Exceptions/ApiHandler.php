<?php

namespace App\Exceptions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
trait ApiHandler
{
    /**
     * Tratamento de erros personalizados
     * 
     * @return \Illuminate\Http\Response
     */
    public function tratarErros(Throwable $exception):\Illuminate\Http\Response|false
    {
        if($exception instanceof ModelNotFoundException){
            return $this->modelNotFoundException($exception);
        }
        if($exception instanceof ValidationException){
            return $this->validationException($exception);
        }
        if($exception instanceof notFoundHttpException){
            return $this->notFoundHttpException($exception);
        }
        return false;
    }

    /**
     * 
     * Retorna erro quando não encontra o registro
     */
    public function modelNotFoundException(ModelNotFoundException $exception)
    {
        return $this->respostaPadrao("registro-nao-encontrado","O sistema não encontrou o registro que você está buscando",404);
    }


    /**
     * Retorna erro quando os dados não são válidos
     */
    public function validationException(ValidationException $e)
    {
        return $this->respostaPadrao('error-validation','Os dados enviados são inválidos',400,$e->errors());
    }

    public function notFoundHttpException(NotFoundHttpException $e)
    {
        return $this->respostaPadrao('url-error','A url buscada não existe',404);
    }
    /**
     * Retorna uma resposta padrão para os erros da API
     */
    public function respostaPadrao(string $code, string $message, int $status, array $errors = null)
    {
        $dadosRespostas = [
            'code'=>$code,
            'message'=>$message,
            'status'=>$status,
        ];
        if($errors){
            $dadosRespostas = $dadosRespostas + ['errors'=>$errors];
        }
        return response($dadosRespostas,$status);
    }
}
