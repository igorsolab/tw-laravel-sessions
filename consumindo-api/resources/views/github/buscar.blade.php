@extends('layout.base')

@section('conteudo')
<h2>Lista de repositórios</h2>
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">First</th>
        <th scope="col">Last</th>
        <th scope="col">Handle</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($repositories as $repository)
            <tr>
                <th scope="row">{{$repository["id"]}}</th>
                <td>{{$repository["name"]}}</td>
                <td>{{$repository["html_url"]}}</td>
                <td>{{$repository["score"]}}</td>
            </tr>
        @endforeach
    </tbody>
  </table>
@endsection