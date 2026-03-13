<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; // 後ほどモデルを作成します
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        // 特集イベント（固定データ）
        $featuredEvents = collect([
            (object)[
                'title' => '世田谷公園 週末マーケット',
                'event_date' => Carbon::parse('2026-04-05'),
                'image_path' => 'events/sample1.jpg',
            ]
        ]);

        // データベースからイベントを取得
        $events = Event::orderBy('event_date', 'asc')->get();

        return view('events.index', compact('featuredEvents', 'events'));
    }

    public function show($id)
    {
        // 指定されたIDのイベントを取得
        $event = Event::findOrFail($id);

        // 詳細画面のビューを返す
        return view('events.show', compact('event'));
    }
}