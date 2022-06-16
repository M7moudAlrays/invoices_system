<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{ 
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoices::all() ;
        sections::select('section_name');
        // return view('invoices.invoices' , ['data'=>$invoices]) ;
        return view('invoices.invoices' ,compact('invoices')) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections= sections::All();
        return view ('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            invoices::create
            ([       
                    'invoice_number' => $request-> invoice_number,
                    'invoice_Date' =>$request-> invoice_Date,
                    'Due_date' =>$request->Due_date,
                    'product'=>$request->product,
                    'section_id'=>$request->Section,
                    'Amount_collection'=>$request->Amount_collection,
                    'Amount_Commission'=>$request->Amount_Commission,
                    'Discount'=>$request->Discount,
                    'Value_VAT'=>$request->Value_VAT,
                    'Rate_VAT'=>$request->Rate_VAT,
                    'Total'=>$request->Total,
                    'Status' => 'غير مدفوعه',
                    'Value_Status'=> 2,
                    'note'=>$request->note,
            ]);
            $invoice_id = invoices::latest()->first()->id ;

            invoices_details::create
            ([
                'id_Invoice' => $invoice_id ,
                'invoice_number' => $request-> invoice_number ,
                'product' => $request -> product ,
                'section'  =>$request->Section ,
                'Status' => 'غير مدفوعه' ,
                'Value_Status'=> 2 ,
                'note'=>$request->note,
                'user' => (Auth::user()->name)
            ]) ;

            if($request ->hasFile('pic'))
            {
                $invoice_id = Invoices::latest()->first()->id;
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                $attach = new invoice_attachments();
                $attach->file_name = $file_name;
                $attach->invoice_number = $invoice_number;
                $attach->Created_by = Auth::user()->name;
                $attach->invoice_id = $invoice_id;
                $attach->save();

                // move pic
                $imageName = $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
            }
            session()->flash('Add', 'تم اضافة الفاتوره بنجاح ');
            return back() ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice= invoices::where('id',$id)->first() ;
        $sections = sections::All();
        return view ('invoices.status_update',compact('invoice','sections')) ;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice  = invoices::where('id',$id)->first() ; 
        $sections = sections::All();
        return view ('invoices.edit_invoice',compact('invoice' ,'sections'));
    }


    public function update(Request $request ,$id)
    {
        $invoice = invoices::findorfail($id) ;

        // $invoice ->update($request->all()) ;

        $invoice->update([
        'invoice_number' => $request->invoice_number,
        'invoice_Date' => $request->invoice_Date,
        'Due_date' => $request->Due_date,
        'product' => $request->product,
        'section_id' => $request->Section,
        'Amount_collection' => $request->Amount_collection,
        'Amount_Commission' => $request->Amount_Commission,
        'Discount' => $request->Discount,
        'Value_VAT' => $request->Value_VAT,
        'Rate_VAT' => $request->Rate_VAT,
        'Total' => $request->Total,
        'note' => $request->note,
        ]) ;
        
        Session()->flash('delete') ;
        return redirect()->back() ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */    

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();

        $id_page =$request->id_page;

        if (!$id_page==2) 
        {
            if (!empty($Details->invoice_number)) 
            {
                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }
            $invoices->forceDelete() ;
            Session()->flash('delete', 'تم حذف الفاتورة بنجاح') ;
            return redirect()->back() ;
        }

        else 
        {
            $invoices->delete();
            session()->flash('archive_invoice' , 'تم أرشفة الفاتورة بنجاح');
            return redirect('/archive');
        }
    }

    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }

    public function status_update($id, Request $request)
    {
        $invoice = invoices::findOrfail($id) ;

        if($request->Status === "مدفوعة")
        {
            $invoice->update
            ([
                    'Value_Status' => 1 ,
                    'Status' => $request->Status,
                    'Payment_Date' => $request->Payment_Date,
            ]) ;

            invoices_details::create
            ([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1 ,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]) ;
        }

        else
        {
            $invoice->update
            ([
                    'Value_Status' => 3 ,
                    'Status' => $request->Status,
                    'Payment_Date' => $request->Payment_Date,
            ]) ;

            invoices_details::create
            ([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3 ,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]) ;
        }

        session()->flash('Status_Update');
        return redirect('/invoices');

    }

    public function invoices_paid()
    {
        $invoices = invoices::where('Value_Status', 1)->get();
        $section = sections::all() ;
        return view('invoices.invoices_paid', compact('invoices', 'section')) ;
    }
    public function invoices_unpaid()
    {
        $invoices = invoices::where('Value_Status', 2)->get();
        $section = sections::all() ;
        return view('invoices.invoices_unpaid', compact('invoices', 'section')) ;
    }
    public function invoices_Partial()
    {
        $invoices = invoices::where('Value_Status', 3)->get();
        $section = sections::all() ;
        return view('invoices.invoices_Partial', compact('invoices', 'section')) ;
    }

}

