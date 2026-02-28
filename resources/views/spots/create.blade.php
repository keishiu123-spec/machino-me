<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>まちの目 - 報告</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-xl font-bold mb-4 text-gray-800">通学路の危険を報告</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('spots.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">危険のカテゴリー</label>
                <select name="category" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-500 bg-gray-50">
                    <option value="車が止まらない">車が止まらない</option>
                    <option value="死角あり">死角あり</option>
                    <option value="ガードレールなし">ガードレールなし</option>
                    <option value="不審者リスク">不審者リスク</option>
                    <option value="その他">その他</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">現場の写真（任意）</label>
                <input type="file" name="image" accept="image/*" capture="environment"
                       class="w-full border p-3 rounded-lg bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-2 italic">※タップするとカメラが起動します</p>
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2 text-gray-700">補足メモ（任意）</label>
                <textarea name="note" class="w-full border p-3 rounded-lg bg-gray-50" rows="3" placeholder="例：朝8時頃に抜け道利用の車が多いです"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-lg shadow-lg hover:bg-blue-700 active:scale-95 transition-transform">
                この地点を報告する
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-400">
            <p>※投稿すると「安全マップ」に即時反映されます</p>
        </div>
    </div>

    <script>
        // ページ読み込み時にGPSを取得
        navigator.geolocation.getCurrentPosition(pos => {
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('lng').value = pos.coords.longitude;
            console.log('位置情報を特定しました');
        }, err => {
            alert('位置情報の取得を許可してください。位置情報がないと正しくマッピングされません。');
        });
    </script>
</body>
</html>