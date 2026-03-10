<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {

        // Total copies, available copies, damaged copies
        $data = Book::selectRaw('id, category_id, title, total_copies, (total_copies - COALESCE(damaged_copies,0)) as good_copies, COALESCE(damaged_copies,0) as damaged_copies')
            ->with('category')
            ->get();

        return response()->json($data);
    }
}
