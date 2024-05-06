<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function getRank()
    {
        try {
            $movies = DB::table('movie_link')
                ->join('movie', 'movie_link.id', '=', 'movie.link_id')
                ->orderBy('movie.rank')
                ->take(10)
                ->get();

            return view('ranking', ['movies' => $movies]);
        } catch (\Exception $e) {
            return view('error', ['error' => $e->getMessage()]);
        }
    }
    public function getOptions()
    {
        try {
            $categories = DB::table('moviecategory')->select('id', 'name')->get();
            
            return view('search', ['categories'=>$categories]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public function getValue($x)
    {
        try {
            if ($x != 0) {
                DB::beginTransaction();
                DB::statement("CREATE TEMPORARY TABLE temp_table AS
                                SELECT movie_link.poster_link, movie.point, movie.rank, movie.id
                                FROM movie_link, movie
                                JOIN moviecategory ON movie.category_id = moviecategory.id
                                WHERE movie.link_id = movie_link.id AND moviecategory.id = ?", [$x]);

                DB::statement("UPDATE temp_table
                                JOIN (
                                    SELECT m.id, COUNT(DISTINCT CASE WHEN s.point > m.point THEN s.point ELSE NULL END) + 1 AS new_rank
                                    FROM temp_table AS m
                                    LEFT JOIN temp_table AS s ON m.point < s.point
                                    GROUP BY m.id
                                ) AS subquery ON temp_table.id = subquery.id
                                SET temp_table.rank = subquery.new_rank");

                $movies = DB::table('temp_table')->orderBy('rank')->take(18)->get();
            } else {
                $movies = DB::table('movie_link')
                                ->join('movie', 'movie.link_id', '=', 'movie_link.id')
                                ->orderBy('movie.rank')
                                ->take(18)
                                ->get();
            }

            return view('search', ['movies' => $movies]);
        } catch (\Exception $e) {
            DB::rollBack();
            return view('error', ['error' => $e->getMessage()]);
        } finally {
            DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_table");
        }
    }
}
