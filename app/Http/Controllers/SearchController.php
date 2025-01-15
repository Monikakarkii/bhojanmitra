<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;

class SearchController extends Controller
{
    public function searchTables(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $tables = Table::when($keyword, function ($query, $keyword) {
            return $query->where('table_number', 'LIKE', '%' . $keyword . '%');
        })->paginate(10);

        return response()->json([
            'tables' => $tables
        ]);
    }
}
