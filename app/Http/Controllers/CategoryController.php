<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // created index, add, edit, update, delete function for category category name , used try catch 
    public function index()
    {
   
        $category = Category::all();
        return response()->json($category);

    }

    public function add(Request $request)
    {
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->save();

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

        $category = Category::find($id);
        return response()->json($category);

    }

    public function update(Request $request, $id)
    {
        try {

            $category = Category::find($id);
            $category->name = $request->name;
            $category->save();

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

            $category = Category::find($id);
            $category->delete();

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
