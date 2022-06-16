<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductsController extends Controller
{

    public function index()
    {
        $products = products::all();
        $sections = sections::select('id' , 'section_name')->get() ;
        return view('products.products', ['data'=>$products , 'data2' =>$sections]);
        // return view('products.products');
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate
        (
            [
            'Product_name' => 'required|unique:products|max:255',
            ],
            
            [
            'Product_name.required' =>'يرجي ادخال اسم المنتج',
            'Product_name.unique' =>'اسم المنتج مسجل مسبقا',
            ]
        );

            products::create
            (
                [
                'Product_name' => $request->Product_name,
                'description' => $request->description,
                'section_id' => $request->section_id,
                ]
            );

            session()->flash('Add', 'تم اضافة المنتــج بنجاح ');
            return redirect('products');
    }

    public function show(products $products)
    {
        //
    }

    public function edit(products $products)
    {
        //
    }

    public function update(Request $request, products $products)
    {
        $sec_id = sections::where('section_name', $request->section_name)->first()->id;
        $Products = Products::findOrFail($request->pro_id);
 
        $Products->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $sec_id,
        ]);
 
        session()->flash('edit', 'تم تعديل المنتج بنجاح');
        return back();
    }

    public function destroy(Request $request)
    {
        $id = $request->pro_id ;
        products::find($id)->delete() ;

        session()->flash('delete' , 'تم حذف المنتج بنجــاح') ;
        return Redirect('products') ;
    }
}
