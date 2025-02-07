<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Models\Transaction;

class StoreController extends Controller
{
    // created index , add, edit, update, delete function for store product name, category_id, image, qty, price_by, price_sell, used try catch
    public function index()
    {
        $sort = \Request::get('sort'); // asc or desc
        $perpage = \Request::get('perpage'); // 10, 20, 30, 40, 50
        $catid = \Request::get('catid'); // category id all
        $search = \Request::get('search'); // search by name, price_buy

        $store = Store::join('categories', 'stores.category_id', '=', 'categories.id')
            ->select('stores.*', 'categories.name as category_name')
            ->orderBy('stores.id', $sort);

            // ຖ້າ catid ບໍ່ເປັນ all ໃຫ້ສະແດງສິນຄ້າທີ່ມີ category_id ທີ່ລະບຸ
            if($catid != 'all') {
                $store->where('stores.category_id', $catid);
            }

            // search ຊື່ສິນຄ້າ ແລະ ລາຄາຊື່ສິນຄ້າ
            $store = $store->where(
                function($query) use ($search) {
                    $query->where('stores.name', 'like', '%'.$search.'%')
                        ->orWhere('stores.price_buy', 'like', '%'.$search.'%');
                }
            )

            // paginate ຂໍ້ມູນທີ່ສະແດງ
            ->paginate($perpage)
            ->toArray();

        return response()->json($store);


   

    }

    public function add(Request $request)
    {
        try {

            // path image
            $upload_path = "assets/img";

            // upload image
            if($request->hasFile('image')) {
                // generate image name
                $image_name = "img".time().'.'.$request->image->getClientOriginalExtension();
                // upload image
                $request->image->move(public_path($upload_path), $image_name);
            } else {
                $image_name = null;
            }   


            $store = new Store();
            $store->name = $request->name;
            $store->category_id = $request->category_id;
            $store->image = $image_name;
            $store->qty = $request->qty;
            $store->price_buy = $request->price_buy;
            $store->price_sell = $request->price_sell;
            $store->save();

            $product_id = $store->id;

            /// add transection 

            // create new transection
            $tran_id = 'TRN'.date('YmdHis').rand(100,999);
            $tran = new Transaction([
                "tran_id" => $tran_id, // date(ymds).rand(1000,9999)
                "tran_type" => "expense",
                "product_id"=> $product_id,
                "qty"=> $request->qty,
                "price"=> $request->price_buy*$request->qty,
                "detail"=> "ນຳເຂົ້າສິນຄ້າ ".$request->name
            ]);
            $tran->save();

            $success = true;
            $message = "ບັນທຶກຂໍ້ມູນສຳເລັດ!";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function edit($id)
    {
        $store = Store::find($id);
        return response()->json($store);
    }

    public function update(Request $request, $id)
    {
        try {


            // path image
            $upload_path = "assets/img";

            $store = Store::find($id);

            // upload image
            if($request->hasFile('image')) {

                // ລຶບຮູບອັບເກົ່າ
                if($store->image){
                    if(file_exists(public_path($upload_path.'/'.$store->image))){
                        unlink(public_path($upload_path.'/'.$store->image));
                    }
                }

                // generate image name
                $image_name = "img".time().'.'.$request->image->getClientOriginalExtension();
                // upload image
                $request->image->move(public_path($upload_path), $image_name);

                /// ອັບເດດຂໍ້ມູນ
                $store = Store::find($id);
                $store->name = $request->name;
                $store->category_id = $request->category_id;
                $store->image = $image_name;
                $store->qty = $request->qty;
                $store->price_buy = $request->price_buy;
                $store->price_sell = $request->price_sell;
                $store->save();

            } else {
                $image_name = null;

                if($request->image){

                    $store = Store::find($id);
                    $store->name = $request->name;
                    $store->category_id = $request->category_id;
                    // $store->image = $request->image;
                    $store->qty = $request->qty;
                    $store->price_buy = $request->price_buy;
                    $store->price_sell = $request->price_sell;
                    $store->save();


                } else {

                    // ລຶບຮູບອັບເກົ່າ
                    if($store->image){
                        if(file_exists(public_path($upload_path.'/'.$store->image))){
                            unlink(public_path($upload_path.'/'.$store->image));
                        }
                    }


                    $store = Store::find($id);
                    $store->name = $request->name;
                    $store->category_id = $request->category_id;
                    $store->image = null;
                    $store->qty = $request->qty;
                    $store->price_buy = $request->price_buy;
                    $store->price_sell = $request->price_sell;
                    $store->save();

                }
            } 



           

            $success = true;
            $message = "ປັບປຸງຂໍ້ມູນສຳເລັດ!";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);


    }

    public function delete($id)
    {
        try {
            $store = Store::find($id);
             // path image
             $upload_path = "assets/img";
            // ລຶບຮູບອັບເກົ່າ
            if($store->image){
                if(file_exists(public_path($upload_path.'/'.$store->image))){
                    unlink(public_path($upload_path.'/'.$store->image));
                }
            }


            $store->delete();

            $success = true;
            $message = "ລົບຂໍ້ມູນສຳເລັດ!";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
        
    
}
