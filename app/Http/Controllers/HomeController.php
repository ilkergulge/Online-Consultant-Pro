<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $featuredConsultants = User::where('role', 'consultant')->with('consultantProfile.category')->take(6)->get();

        return view('welcome', compact('categories', 'featuredConsultants'));
    }
}
