<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\sections;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class SectionsController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $Sections = sections::all();
//        return view('sections.sections', compact('Sections'));
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create()
//    {
//
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(Request $request)
//    {
//        $validatedData = $request->validate([
//            'section_name'=>'required|unique:sections|max:999',
//            'description'=>'max:999',
//        ],[
//            'section_name.required'=>'يرجى ادخال اسم القسم',
//            'section_name.unique'=>'هذا القسم مسجل بالفعل',
//            'section_name.max'=>'عفوا لقد تخطيت الحد الاقصى من الحروف لاسم القسم (999 حرف)',
//            'description.max'=>'عفوا لقد تخطيت الحد الاقصى من الحروف لوصف القسم (999 حرف)',
//        ]);
//
//            sections::create([
//                'section_name' => $request->section_name,
//                'description' => $request->description,
//                'Created_by' => (Auth::user()->name),
//            ]);
//
//            session()->flash('Add', 'تم اضافة القسم بنجاح');
//            return redirect('/sections');
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(sections $sections)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(sections $sections)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(Request $request, sections $sections)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(sections $sections)
//    {
//        //
//    }
//}


namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['create', 'store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['edit', 'update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }
    public function index()
    {
        $Sections = sections::all();
        return view('sections.sections', compact('Sections'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'section_name'=>'required|unique:sections|max:999',
            'description'=>'max:999',
        ],[
            'section_name.required'=>'يرجى ادخال اسم القسم',
            'section_name.unique'=>'هذا القسم مسجل بالفعل',
            'section_name.max'=>'عفوا لقد تخطيت الحد الاقصى من الحروف لاسم القسم (999 حرف)',
            'description.max'=>'عفوا لقد تخطيت الحد الاقصى من الحروف لوصف القسم (999 حرف)',
        ]);

            sections::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'Created_by' => (Auth::user()->name),
            ]);

            session()->flash('Add', 'تم اضافة القسم بنجاح');
            return redirect('/sections');
    }


    public function show(sections $sections)
    {
        //
    }



    public function edit(sections $sections)
    {
        //
    }


    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
            'description' => 'required',
        ], [

            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit', 'تم تعديل القسم بنجاج');
        return redirect('/sections');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}

