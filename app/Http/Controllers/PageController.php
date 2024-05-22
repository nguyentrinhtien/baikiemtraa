<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class PageController extends Controller
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),
        [
            "name"  => "required",
            "description" => "required", 
            "unit_price" => "required",
            "promotion_price" => "required",
            "unit" =>"required",
            'image_file'=>'mimes:jpeg,jpg,png,gif|max:10000'
        ]);
    }
    public function postSearch(Request $request)
    {
        $query = $request->input('query');
    
        $foods = tFood::with('mf') // Eager load the related mf table
                    ->where('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('description', 'LIKE', '%' . $query . '%')
                    ->orWhereHas('mf', function ($q) use ($query) { 
                        $q->where('mf_name', 'LIKE', '%' . $query . '%');
                    })
                    ->paginate(5);
    
        return view('baikiemtra.index_show', compact('foods'))->with('search_query', $query);
    }
}
