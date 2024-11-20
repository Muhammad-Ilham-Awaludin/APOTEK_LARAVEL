<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Medicine;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
// use PDF;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::where('created_at', 'LIKE', '%'. $request->search_date. '%')->orderBy('created_at', 'ASC')->simplePaginate(10);
        return view('order.kasir.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $medicines = Medicine::all();
        return view('order.kasir.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);

        //mencari jumlah item yang sama pada array
        $arrayDistinct = array_count_values($request->medicines);
        // menyiapkan array kosong untuk menampung format array baru
        $arrayAssocMedicines = [];

        foreach ($arrayDistinct as $id => $count) {
            // mencari data obat berdasarkan id (obat yang nanti di pilih)
            $medicine = Medicine::where('id', $id)->first();

            if ($medicine['stock'] < $count) {
                $valueBefore = [
                    "name_customer" => $request->name_customer,
                    "medicines" => $request->medicines,
                ];
                $msg = "Obat " . $medicine['name'] . " sisa stock : " . $medicine['stock'] . ", Tidak dapat melakukan proses pembelian";
                return redirect()->back()->withInput()->with('failed', $msg, $valueBefore);
            } else {
                $medicine['stock'] -= $count;
                $medicine->save();
            }

            $subPrice = $medicine['price'] * $count;

            $arrayItem = [
                "id" => $id,
                "name_medicine" => $medicine['name'],
                "qty" => $count,
                "price" => $medicine['price'],
                "sub_price" => $subPrice,
            ];

            array_push($arrayAssocMedicines, $arrayItem);
        }

        $totalPrice = 0;

        foreach ($arrayAssocMedicines as $item) {
            $totalPrice += (int)$item['sub_price'];
        }

        // harga total price ditambah 10%
        $priceWithPPN = $totalPrice + ($totalPrice * 0.1);
        $proses = Order::create([
            'user_id' => Auth::user()->id,
            'medicines' => $arrayAssocMedicines,
            'name_customer' => $request->name_customer,
            'total_price' => $priceWithPPN,
        ]);

        if ($proses) {
            // foreach ($arrayAssocMedicines as $item) {
            //     $medicine = Medicine::find($item['id']);
            //     $medicine->stock -= $item['qty'];
            //     $medicine->save();
            // }
            $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            return redirect()->route('kasir.order.print', $order['id']);
        } else {
            return redirect()->back()->with('failed', 'Gagal membuat data pembelian, Silahkan coba kembali dengan data yang sesuai!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $order = Order::find($id);
        return  view('order.kasir.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function downloadPdf($id)
    {
        //ambil data berdasarkan id yang ada di struk dan dipastikan terformat array
        $order = Order::find($id)->toArray();
        //kita akan share data dengan inisial awal agar bisa digunakan ke blade manapun
        view()->share('order', $order);
        //ini akan meload view halaman downloadnya
        $pdf = PDF::loadView('order.kasir.download-pdf', $order);
        //tinggal kita download
        return $pdf->download('invoice.pdf');
    }
    public function riwayat()
    {
        // with : mengambil hasil relasi dari PK dan FK nya. valuenya == nama func relasi hasMoney/belongsTo yang ada di modelnya
        $orders = Order::with('user')->simplePaginate(10);
        return view('order.admin.index', compact('orders'));
    }
    public function exportExcel()
    {
        return Excel::download(new OrderExport, 'rekap-pembelian.xlsx');
    }
}
