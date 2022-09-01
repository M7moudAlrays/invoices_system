<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{
   
    function __construct()
    {
            $this->middleware('permission:اضافة مرفق', ['only' => ['store']]); 
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate
        (
            [
                'file_name' => 'mimes: pdf,jpeg,png,jpg',
            ] ,
            [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg' 
            ]
        );

            $f = $request->file_name ;
            $new_file = $f->getClientOriginalName() ;

            $new_attch = new invoice_attachments() ;
            
            $new_attch->file_name = $new_file ;
            $new_attch->invoice_number = $request->invoice_number ;
            $new_attch->invoice_id = $request->invoice_id ;
            $new_attch->Created_by = Auth::user()->name ;
            $new_attch->save() ;

            $request->file_name->move(public_path('Attachments/' .$request->invoice_number),$new_file) ;

            session()->flash('Add' , 'تم إضــــافة المرفق بنجــــاح') ;
            return  back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function show(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function edit(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoice_attachments $invoice_attachments)
    {
        //
    }
}
