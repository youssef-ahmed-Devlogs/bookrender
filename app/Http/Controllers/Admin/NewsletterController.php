<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewslettersExport;
use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(10);
        $settings = Setting::first();

        return view("admin.newsletters.index", compact("newsletters", "settings"));
    }

    public function export()
    {
        return Excel::download(new NewslettersExport, 'newsletters.xlsx');
    }
}
