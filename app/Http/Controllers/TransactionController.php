<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Bill;
use App\Models\BillList;
use App\Models\Store;

class TransactionController extends Controller
{
    //

    public function add(Request $request){

        try {

            // ສ້າງຂໍ້ມູນໃບບິນ

            $bill_id = 'B'.date('YmdHis').rand(100,999);
            $bill = new Bill([
                "bill_id" => $bill_id,
                "customer_name" => $request->customer_name,
                "customer_tel" => $request->customer_tel,
            ]);
            $bill->save();



             // foreach listorder from request
             foreach($request->listorder as $item){

                // generate tran_id
                $tran_id = 'TRN'.date('YmdHis').rand(100,999);

                // create new transection
                $tran = new Transaction([
                    "tran_id" => $tran_id, // date(ymds).rand(1000,9999)
                    "tran_type" => "income",
                    "product_id"=> $item["id"],
                    "qty"=> $item["qty"],
                    "price"=> $item["price"]*$item["qty"],
                    "detail"=> $item["name"]
                ]);
                $tran->save();

                // ບັນທຶກລາຍການໃບບິນ
                $bill_list = new BillList([
                    "bill_id" => $bill_id,
                    "name" => $item["name"],
                    "qty" => $item["qty"],
                    "price" => $item["price"]
                ]);
                $bill_list->save();


                /// ຕັດສະຕ໋ອກ
                $store = Store::find($item["id"]);
                $store_update = Store::find($item["id"]);

                $store_update->update([
                    "qty" => $store->qty - $item["qty"]
                ]);

            }
          

            $success = true;
            $message = "ບັນທຶກຂໍ້ມູນສຳເລັດ!";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
            $bill_id = '';
        }

        $response = [
            'success' => $success,
            'message' => $message,
            'bill_id' => $bill_id
        ];

        return response()->json($response);

    }


    public function index(){
        
        $sort = \Request::get('sort');
        $per_page = \Request::get('perpage');
        $month_type = \Request::get('month_type');
        $dmy = \Request::get('dmy'); // 2025-01-01

     

        $m = explode("-",$dmy)[1];
        $y = explode("-",$dmy)[0];

        // get all transection
        if($month_type == "m"){

            $tran = Transaction::orderBy("id",$sort)
            ->whereYear("created_at",$y) 
            ->whereMonth("created_at",$m)
            ->paginate($per_page)
            ->toArray();

           

        } else if($month_type == "y"){

            $tran = Transaction::orderBy("id",$sort)
            ->whereYear("created_at",$y)
            ->paginate($per_page)
            ->toArray();

        }

        return array_reverse($tran);

    }
}
