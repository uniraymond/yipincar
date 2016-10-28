<?php

namespace App\Http\Controllers;

use App\Yipinlog;
use Illuminate\Http\Request;

use App\Http\Requests;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = Yipinlog::paginate(20);
        return view('history/index', ['histories'=> $histories]);
    }
}
