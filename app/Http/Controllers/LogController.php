<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScrapingLog;
class LogController extends Controller
{
    //
    public function show($id)
    {
        $log = ScrapingLog::findOrFail($id);
        return view('logs.show', compact('log'));
    }

    public function index()
    {
        $logs = ScrapingLog::All();
        return view('logs.index',compact('logs'));
    }
}
