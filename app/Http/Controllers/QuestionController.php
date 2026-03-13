<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Spot;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with(['comments', 'spot'])->latest()->get();

        return view('questions.index', compact('questions'));
    }

    public function create()
    {
        $spots = Spot::orderBy('title')->get(['id', 'title', 'category', 'image_path']);

        return view('questions.create', compact('spots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'note' => 'required',
            'category' => 'required',
            'spot_id' => 'nullable|exists:spots,id',
            'target_age' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('questions', 'public');
        }

        Question::create([
            'title' => $request->title,
            'note' => $request->note,
            'category' => $request->category,
            'spot_id' => $request->spot_id,
            'target_age' => $request->target_age,
            'image_path' => $imagePath,
            'user_id' => 1,
            'status' => 'open',
        ]);

        return redirect()->route('questions.index')->with('success', '質問を投稿しました！');
    }
}
