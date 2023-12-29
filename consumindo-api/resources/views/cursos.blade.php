<h1>teste na view</h1>

<table>
    <thead>
        <th>Curso</th>
        <th>Versão</th>
    </thead>
    @foreach ($cursos as $curso)
        <tr>
            <td>Curso: {{$curso['nome']}}</td>
            <td>Versão: {{$curso['versao']}}</td>    
        </tr>
    @endforeach
</table>