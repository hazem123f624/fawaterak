<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $count_all =invoices::count();
        $unpaid_invoices = invoices::where('Value_Status', 0)->count();
        $paid_invoices = invoices::where('Value_Status', 1)->count();
        $partially_paid_invoices = invoices::where('Value_Status', 2)->count();

        if($paid_invoices == 0){
              $nspainvoices0=0 ;
        }
        else{
             $nspainvoices0 = $paid_invoices/ $count_all*100;
        }

        if($unpaid_invoices == 0){
             $nspainvoices1=0;
        }
        else{
             $nspainvoices1 = $unpaid_invoices/ $count_all*100;
        }

        if($partially_paid_invoices == 0){

             $nspainvoices2=0;
        }
        else{

            $nspainvoices2 = $partially_paid_invoices/ $count_all*100;
        }

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['']) // وضع تسمية واحدة في المحور الأفقي
            ->datasets([
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => '#118B50',
                    'data' => [$nspainvoices0]
                ],
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => '#ec5858',
                    'data' => [$nspainvoices1]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئياً",
                    'backgroundColor' => '#ff9642',
                    'data' => [$nspainvoices2]
                ]
            ])
            ->options([
                'scales' => [
                    'xAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true
                        ]
                    ]],
                    'yAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true
                        ]
                    ]]
                ]
            ]);

        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#118B50','#ff9642'],
                    'data' => [$nspainvoices1, $nspainvoices0,$nspainvoices2]
                ]
            ])
            ->options([
                'scales' => [
                    'xAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true
                        ]
                    ]],
                    'yAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true
                        ]
                    ]]
                ]
            ]);

        return view('home', compact('chartjs','chartjs_2'));


    }
}
