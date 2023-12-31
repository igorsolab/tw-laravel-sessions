@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                            <div class="col-md-5">
                                <h3 class="w-75 p-3">Clientes</h3>
                            </div>
                            <div class="col-md-7 d-flex flex-row align-items-center justify-content-end">
                                <form id="upload" method="POST" action="{{url('admin/import/clients/excel')}}" enctype="multipart/form-data">
                                    <div class="form-group">
                                        @csrf
                                        <label for="planilha">Importar clientes</label>
                                        <input type="file" name="planilha" id="planilha" class="form-control-file"/>
                                    </div>
                                    <script type="text/javascript">
                                        document.getElementById("planilha").onchange = function(){
                                            document.getElementById("upload").submit();
                                        }
                                    </script>
                                </form>
                                <a href="{{url('admin/clients/all/excel')}}" class="btn btn-success">Todos</a>
                                <a href="{{url('admin/clients/search/excel')}}?search={{request('search')}}" class=" btn btn-info">Filtrados</a>
                            </div>
                    </div>

                    <div class="card-body">
                        <a href="{{ url('/admin/clients/create') }}" class="btn btn-success btn-sm" title="Add New Client">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/admin/clients') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Nome</th><th>Email</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($clients as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nome }}</td><td>{{ $item->email }}</td>
                                        <td>
                                            <a href="{{ url('/admin/clients/' . $item->id) }}" title="View Client"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/admin/clients/' . $item->id . '/edit') }}" title="Edit Client"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/admin/clients' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Client" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $clients->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
