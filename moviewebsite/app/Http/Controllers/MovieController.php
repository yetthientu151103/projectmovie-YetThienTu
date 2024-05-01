<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function getOptions()
    {
        // Truy vấn cơ sở dữ liệu để lấy các tùy chọn
        $options = DB::table('moviecategory')->select('id', 'name')->get();

        // Truyền dữ liệu vào view
        return response()->json($options);
    }

    public function getMoviePosters(Request $request){
        $x = $request->input('x');
        // ... (rest of your code)
        $posters = []; // Store your results here
        return view('search', ['posters' => $posters]);
    }
}
