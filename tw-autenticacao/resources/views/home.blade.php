@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    Acessar o cadastro de artigos <a href="{{route('posts.index')}}" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
