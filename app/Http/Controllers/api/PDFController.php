<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFController extends Controller
{
    public function index()
    {
        $id ="1234rfgbnmwdqoty5";
        $data =[
            'name'=> 'Isaac',
            'event' => 'BECON',
            'date' => '2023-06-18',
            'time' => '10:00',
            'quantity' => 4,
            'id' => '01h5q2h47tdggybqs8jggq4hv8',
            'eventOwner'=>'EGFM',
            'location'=>'Faith Plaza Bariga',
            'link' => config('app.url').'/api/v1/tickets/'.$id,
        ];
        $pdf = Pdf::loadView('ticket', $data);
            // echo config('app.url').'/api/v1/tickets/'.$id;
        return $pdf->download('ticket.pdf');
       
    }
}
