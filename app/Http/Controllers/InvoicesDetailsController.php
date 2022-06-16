<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\sections;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoices_details $invoices_details)
    {
        //
    }
    
    public function get_invoice_details ($id)
    {
        sections::select('section_name');
        $invoices = invoices::where('id' , $id)->first();
        $details = invoices_details::where('id_Invoice' ,$id)->get();
        $attachments = invoice_attachments::where('invoice_id' ,$id)->get();
        return view ('invoices.details_invoices' , compact('invoices','details','attachments'));
    }

    public function show_file($invoice_num , $file_name)

    {
        $files = Storage::disk('public_fiels')->getDriver()->getAdapter()->applyPathPrefix($invoice_num .'/'. $file_name);
        return response()->file($files) ;
    }

    public function downlaod_file ($invoice_num ,$file_name)
    {
        $files = Storage::disk('public_fiels')->getDriver()->getAdapter()->applyPathPrefix($invoice_num .'/'. $file_name);
        return response()->download($files) ;
    }

    public function delete_file (Request $request)
    {
        $invoice_attchment  = invoice_attachments::findorfail($request->id_file) ;
        $invoice_attchment->delete() ;
        Storage::disk('public_fiels')->delete($request->invoice_number .'/' .$request->file_name) ;
        session()->flash('delete' , 'تم حذف المرفق بنجاح') ;
        return back() ;
    }
    
}


