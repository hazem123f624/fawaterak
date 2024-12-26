<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;


class Invoices_Report extends Controller
{
    public function index()
    {

        return view('reports.invoices_report');

    }

    public function Search_invoices(Request $request)
    {

        $rdio = $request->rdio;


        // في حالة البحث بنوع الفاتورة

        if ($rdio == 1) {
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $type = $request->type;


            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {


                if($request->type=='كل الفواتير'){

                    $invoices = invoices::all();
                    $type = $request->type;

                }else{

                 $invoices = invoices::select('*')->where('Status', '=', $request->type)->get();
                 $type = $request->type;

                }

             return view('reports.invoices_report', compact('type'))->withDetails($invoices);


            }elseif($request->type=='كل الفواتير'&&!empty($start_at) &&!empty( $end_at)){
             // في حالة تحديد تاريخ استحقاق

              $invoices = invoices::whereBetween('invoice_Date',[$start_at,$end_at])->get();
              return view('reports.invoices_report', compact('type', 'start_at', 'end_at'))->withDetails($invoices);

            }
        }else {

            $invoices = invoices::select('*')->where('invoice_number', '=', $request->invoice_number)->get();
            return view('reports.invoices_report')->withDetails($invoices);

        }
    }
}

//namespace App\Http\Controllers;
//
//use App\Models\invoices;
//use Illuminate\Http\Request;
//
//class Invoices_Report extends Controller
//{
//    public function index()
//    {
//
//        return view('reports.invoices_report');
//
//    }
//
//    public function Search_invoices(Request $request)
//    {
//
//        $rdio = $request->rdio;
//
//
//        // في حالة البحث بنوع الفاتورة
//
//        if ($rdio == 1) {
//
//
//            // في حالة عدم تحديد تاريخ
//            if ($request->type && $request->start_at == '' && $request->end_at == '') {
//
//                $invoices = invoices::select('*')->where('Status', '=', $request->type)->get();
//                $type = $request->type;
//                return view('reports.invoices_report', compact('type'))->withDetails($invoices);
//            } // في حالة تحديد تاريخ استحقاق
//            else {
//
//                $start_at = date($request->start_at);
//                $end_at = date($request->end_at);
//                $type = $request->type;
//
//                $invoices = invoices::whereBetween('invoice_Date', [$start_at, $end_at])->where('Status', '=', $request->type)->get();
//                return view('reports.invoices_report', compact('type', 'start_at', 'end_at'))->withDetails($invoices);
//
//            }
//
//
//        }
//
////====================================================================
//
//// في البحث برقم الفاتورة
//        else {
//
//            $invoices = invoices::select('*')->where('invoice_number', '=', $request->invoice_number)->get();
//            return view('reports.invoices_report')->withDetails($invoices);
//
//        }
//
//
//    }
//
//}
