<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome()
    {
        $page = Page::find(1);

        return view('page', compact('page'));
    }

    public function consultation()
    {
        $page = Page::findOrFail(2);

        return view('page', compact('page'));
    }
}
