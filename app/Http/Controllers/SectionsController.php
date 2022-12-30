<?php

namespace App\Http\Controllers;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }

    public function index()
    {
        // $sections = sections::select('id' , 'section_name','description','created_by')->get() ;

        $sections = sections::all();
        return view('sections.sections' ,['data'=>$sections]) ;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $request->validate
        // (
        //     [
        //     'section_name' => 'required|unique:sections|max:255',
        //     ],
            
        //     [
        //     'section_name.required' =>'يرجي ادخال اسم القسم',
        //     'section_name.unique' =>'اسم القسم مسجل مسبقا',
        //     ]
        // );

            sections::create
            (
                [
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => (Auth::user()->name),
                ]
            );

            session()->flash('Add', 'تم اضافة القسم بنجاح ');
            return redirect('sections');
    }

    public function show(sections $sections)
    {
        
    }

    
    public function edit(sections $sections)
    {
        //
    }

    
    public function update(Request $request)
    {
        $id = $request->id;
        $this->validate($request,
            [
            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
            ],
            [
            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',
            ]
        );

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect('/sections');
    }

    
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('sections');
    }
}
