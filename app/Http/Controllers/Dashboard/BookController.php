<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();
        $books = Project::where('user_id', auth()->user()->id)
            ->when(request()->get('search'), function ($query, $value) {
                $query->where('title', 'LIKE', "%{$value}%");
                $query->orWhere('second_title', 'LIKE', "%{$value}%");
                $query->orWhere('description', 'LIKE', "%{$value}%");
                $query->orWhere('author', 'LIKE', "%{$value}%");
            })
            ->get();

        return view('dashboard.books.index', [
            'settings' => $settings,
            'books' => $books,
        ]);
    }
}
