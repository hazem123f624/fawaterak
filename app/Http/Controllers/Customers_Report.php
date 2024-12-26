<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\invoices;
//use App\Models\products;
//use App\Models\sections;
//use Illuminate\Http\Request;
//
//class Customers_Report extends Controller
//{
//    public function index()
//    {
//        $sections = sections::all();
//        return view('reports.customers_report', compact('sections',));
//
//    }
//
//
//    public function Search_customers(Request $request)
//    {
//
//
//// في حالة البحث بدون التاريخ
//        if ( empty($request->Section)&&empty($request->product) && $request->start_at == '' && $request->end_at == '') {
////            $invoices = invoices::select('*')->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
////            $sections = sections::all();
////            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
//                $product = products::all();
//                $invoices = invoices::all();
//                $section = sections::all();
//                return view('reports.customers_report', compact('invoices','section','product'))->withDetails($invoices);
//
//
////                $invoices = invoices::select('*')->where('Status', '=', $request->type)->get();
////                $type = $request->type;
////           }
//            return ($request);
//        } // في حالة البحث بتاريخ
//        else {
//
//            $start_at = date($request->start_at);
//            $end_at = date($request->end_at);
//
//            $invoices = invoices::whereBetween('invoice_Date', [$start_at, $end_at])->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
//            $sections = sections::all();
//            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
//
//
//        }
//
//
//    }
//
//}


namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class Customers_Report extends Controller
{
    public function index()
    {
        $sections = Sections::all();
        return view('reports.customers_report', compact('sections'));
    }

    public function Search_customers(Request $request)
    {
        // استلام البيانات من الـ request
        $section = $request->Section;
        $product = $request->product;
        $start_at = $request->start_at ? date('Y-m-d', strtotime($request->start_at)) : null;
        $end_at = $request->end_at ? date('Y-m-d', strtotime($request->end_at)) : null;

        $query = Invoices::query();  // بداية الاستعلام من جدول الفواتير

        // تصفية الفواتير حسب القسم
        if ($section) {
            $query->where('section_id', $section);
        }

        // تصفية الفواتير حسب المنتج (إذا كان محددًا)
        if ($product) {
            $query->where('product', $product);
        }

        // تصفية الفواتير حسب الفترة الزمنية (إذا كانت موجودة)
        if ($start_at && $end_at) {
            $query->whereBetween('invoice_Date', [$start_at, $end_at]);
        }

        // تنفيذ الاستعلام
        $invoices = $query->get();

        // جلب جميع الأقسام لإظهارها في واجهة المستخدم
        $sections = Sections::all();

        // عرض البيانات في الواجهة
        return view('reports.customers_report', compact('sections'))->withDetails($invoices);
    }

    public function getAllSectionsAndProducts()
    {
        // إرجاع الأقسام والمنتجات
        $sections = Sections::pluck('section_name', 'id');
        $products = Invoices::pluck('product'); // يمكنك تعديل هذا حسب الحاجة
        return response()->json([
            'sections' => $sections,
            'products' => $products,
        ]);
    }

    public function getAllProducts()
    {
        $products = Invoices::pluck('product_name', 'id');  // جلب جميع المنتجات من جدول الفواتير
        return response()->json([
            'products' => $products
        ]);
    }
    public function getProductsBySection($sectionId)
    {
        try {
            // تأكد أن القسم موجود
            if (!$sectionId) {
                return response()->json(['error' => 'Invalid Section ID'], 400);
            }

            // جلب المنتجات بناءً على القسم
            $products = products::where('section_id', $sectionId)->pluck('product_name', 'id');

            // إرجاع المنتجات كـ JSON
            return response()->json([
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            // تسجيل الخطأ وإرجاع استجابة بخطأ
            Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

}
