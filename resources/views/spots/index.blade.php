「写真を表示する機能」を組み込んだ、index.blade.php（地図一覧画面）の完全なコードです。

Leaflet.jsを使用して、ピンをクリックした際に投稿された写真がポップアップ内に表示されるように構築しています。

HTML
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>まちの目 - 安全マップ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 600px; width: 100%; }
        /* ポップアップ内の画像サイズ調整 */
        .leaflet-popup-content img {
            border-radius: 8px;
            margin-top: 8px;
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">地域のヒヤリハットマップ</h1>
            <a href="{{ route('spots.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 shadow-md transition-all">
                ＋ 新しく報告する
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div id="map" class="rounded-xl shadow-lg border-4 border-white"></div>

        <div class="mt-6 bg-white p-6 rounded-xl shadow-md">
            <h2 class="font-bold text-lg mb-4 border-b pb-2">最近の報告一覧</h2>
            <div class="space-y-4">
                @foreach($spots as $spot)
                    <div class="flex items-start space-x-4 border-b last:border-0 pb-4">
                        <div class="flex-1">
                            <span class="inline-block bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded mb-1">
                                {{ $spot->category }}
                            </span>
                            <p class="text-gray-700">{{ $spot->note ?? '（メモなし）' }}</p>
                            <span class="text-xs text-gray-400">{{ $spot->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        @if($spot->image_path)
                            <img src="{{ asset('storage/' . $spot->image_path) }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // 1. 地図の初期化（日本全体を表示）
        const map = L.map('map').setView([35.681236, 139.767125], 13);

        // 2. 地図タイル（OpenStreetMap）の読み込み
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 3. PHPから渡された投稿データをJSに展開
        const spots = @json($spots);

        // 4. 各地点にピンを立てる
        const markers = [];
        spots.forEach(spot => {
            if (spot.lat && spot.lng) {
                // ポップアップの内容を構築
                let popupContent = `
                    <div class="p-1">
                        <strong class="text-blue-600 text-lg">${spot.category}</strong><br>
                        <p class="text-gray-600 my-1">${spot.note || ''}</p>
                `;

                // 画像がある場合はimgタグを追加
                if (spot.image_path) {
                    const imageUrl = `/storage/${spot.image_path}`;
                    popupContent += `<img src="${imageUrl}" style="max-width: 100%; height: auto;">`;
                }

                popupContent += `</div>`;

                // 地図にマーカーを追加
                const marker = L.marker([spot.lat, spot.lng])
                    .addTo(map)
                    .bindPopup(popupContent);
                
                markers.push(marker);
            }
        });

        // 5. 投稿がある場合、すべてのピンが見えるように自動調整
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    </script>
</body>
</html>