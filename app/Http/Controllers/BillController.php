<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\BillList;

class BillController extends Controller
{
    //

    public function print_bill($id){

        $bill = Bill::where('bill_id',$id)->get();
        $bill_list = BillList::where('bill_id',$id)->get();

        return view('bill',compact('id','bill','bill_list'));

    }
}
