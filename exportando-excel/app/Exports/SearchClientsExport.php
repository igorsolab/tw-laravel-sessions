<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SearchClientsExport implements FromView
{
    private $clients;

    public function __construct($clients)
    {
        $this->clients = $clients;
    }
    /**
    */
    public function view():View
    {
        return view('admin.clients.excel',[
            'clients'=>$this->clients
        ]);
    }
}
