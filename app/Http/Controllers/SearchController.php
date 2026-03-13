<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmbassadorPost;
use App\Models\Spot;
use App\Models\Question;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $spots = Spot::where('title', 'like', "%{$query}%")->get();
        $questions = Question::where('title', 'like', "%{$query}%")->get();
        $ambassadorPosts = AmbassadorPost::with(['user', 'spot'])
            ->where('message', 'like', "%{$query}%")
            ->get();

        return view('search.index', compact('query', 'spots', 'questions', 'ambassadorPosts'));
    }
}
