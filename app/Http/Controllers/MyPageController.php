<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Question;
use App\Models\Comment;
use App\Models\School;
use App\Models\AmbassadorPost;
use App\Models\User;

class MyPageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 未ログインならプレビュー画面
        if (!$user) {
            $schools = School::orderBy('name')->get();
            return view('mypage.guest', compact('schools'));
        }

        $user->load('mySchool');

        $spotCount = Spot::where('user_id', $user->id)->count();
        $questionCount = Question::where('user_id', $user->id)->count();
        $commentCount = Comment::where('user_id', $user->id)->count();

        $schools = School::orderBy('name')->get();

        // お気に入りスポット（距離情報付き）
        $favorites = $user->favoriteSpots()->with(['reviews'])->get();

        // 拠点校からの距離を計算
        if ($user->mySchool) {
            $schoolLat = $user->mySchool->lat;
            $schoolLng = $user->mySchool->lng;
            $favorites->each(function ($spot) use ($schoolLat, $schoolLng) {
                $spot->distance_meters = $this->haversineDistance($schoolLat, $schoolLng, $spot->lat, $spot->lng);
                $spot->walk_minutes = (int) ceil($spot->distance_meters / 80); // 徒歩80m/分
            });
            $favorites = $favorites->sortBy('distance_meters')->values();
        }

        // 拠点校1km圏内のアンバサダーボイス
        $nearbyPosts = collect();
        if ($user->mySchool) {
            $posts = AmbassadorPost::with(['user', 'spot'])->latest()->take(50)->get();
            $nearbyPosts = $posts->filter(function ($post) use ($user) {
                if (!$post->spot) return false;
                $dist = $this->haversineDistance(
                    $user->mySchool->lat, $user->mySchool->lng,
                    $post->spot->lat, $post->spot->lng
                );
                $post->distance_meters = $dist;
                return $dist <= 1500; // 1.5km圏内
            })->take(5)->values();
        }

        return view('mypage.index', compact(
            'user', 'spotCount', 'questionCount', 'commentCount',
            'schools', 'favorites', 'nearbyPosts'
        ));
    }

    public function updateSchool(Request $request)
    {
        $request->validate(['school_id' => 'nullable|exists:schools,id']);

        $user = auth()->user() ?? User::find(1);
        $user->update(['my_school_id' => $request->school_id]);

        return back()->with('success', '拠点校を設定しました！');
    }

    public function toggleFavorite(Request $request, Spot $spot)
    {
        $user = auth()->user() ?? User::find(1);

        $exists = $user->favoriteSpots()->where('spot_id', $spot->id)->exists();

        if ($exists) {
            $user->favoriteSpots()->detach($spot->id);
            $status = 'removed';
        } else {
            $user->favoriteSpots()->attach($spot->id);
            $status = 'added';
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'お気に入りに追加しました！' : 'お気に入りを解除しました');
    }

    /**
     * Haversine distance in meters
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
