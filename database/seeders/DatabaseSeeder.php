<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\GooglePlacesService;
use App\Models\AmbassadorPost;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 小学校データを先にシード
        $this->call(SchoolSeeder::class);

        $now = Carbon::now();
        $places = new GooglePlacesService();

        // ========== Users ==========
        DB::table('users')->insert([
            ['id'=>1,'name'=>'田中 美咲','email'=>'misaki@example.com','password'=>Hash::make('password'),'nickname'=>'みさきママ','thanks_score'=>85,'badge_level'=>'expert','is_official'=>false,'area_code'=>'setagaya','role'=>'user','avatar_url'=>null,'bio'=>null,'organization_name'=>null,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'佐藤 健太','email'=>'kenta@example.com','password'=>Hash::make('password'),'nickname'=>'けんパパ','thanks_score'=>210,'badge_level'=>'guardian','is_official'=>false,'area_code'=>'setagaya','role'=>'user','avatar_url'=>null,'bio'=>null,'organization_name'=>null,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'name'=>'鈴木 あゆみ','email'=>'ayumi@example.com','password'=>Hash::make('password'),'nickname'=>'あゆママ','thanks_score'=>25,'badge_level'=>'reliable','is_official'=>false,'area_code'=>'setagaya','role'=>'user','avatar_url'=>null,'bio'=>null,'organization_name'=>null,'created_at'=>$now,'updated_at'=>$now],
            // Ambassador users
            ['id'=>4,'name'=>'中村 拓也','email'=>'nakamura@diva-swim.example.com','password'=>Hash::make('password'),'nickname'=>'中村コーチ','thanks_score'=>0,'badge_level'=>'ambassador','is_official'=>true,'area_code'=>'setagaya','role'=>'ambassador','avatar_url'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop','bio'=>'ディーバスイミング代表。子どもたちが水を好きになる指導を心がけています。','organization_name'=>'ディーバスイミング','created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'name'=>'山田 真理子','email'=>'yamada@kurumi-ballet.example.com','password'=>Hash::make('password'),'nickname'=>'真理子先生','thanks_score'=>0,'badge_level'=>'ambassador','is_official'=>true,'area_code'=>'setagaya','role'=>'ambassador','avatar_url'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop','bio'=>'くるみバレエスタジオ主宰。ロシアメソッドをベースに、一人ひとりの身体に合った指導を。','organization_name'=>'くるみバレエスタジオ','created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'name'=>'高橋 誠','email'=>'takahashi@fermat.example.com','password'=>Hash::make('password'),'nickname'=>'高橋先生','thanks_score'=>0,'badge_level'=>'ambassador','is_official'=>true,'area_code'=>'setagaya','role'=>'ambassador','avatar_url'=>'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop','bio'=>'フェルマー数学塾塾長。元エンジニア。「なぜ？」を大切にする授業で考える力を育てます。','organization_name'=>'フェルマー数学塾','created_at'=>$now,'updated_at'=>$now],
        ]);

        // ========== Spots（Places API + フォールバック座標） ==========
        // 各スポットの定義: name, search_query, address, fallback_lat, fallback_lng, + metadata
        $spotDefs = [
            [
                'title' => '弦巻キッカーズ',
                'search_query' => '弦巻小学校 世田谷区',
                'address' => '東京都世田谷区弦巻1丁目9-18',
                'fallback_lat' => 35.63971, 'fallback_lng' => 139.65300,
                'category' => 'スポーツ少年団',
                'note' => '弦巻小を中心に活動する地域密着型サッカー少年団。土日午前に校庭で練習。コーチは保護者OBが中心でアットホーム。当番は月2回ほど、配車当番もあり。',
                'user_id' => 1,
                'image_path' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '6-12',
                'parent_role' => '当番あり（月2回）・配車当番あり',
                'category_tags' => ['サッカー','少年団','弦巻小','土日練習','区内リーグ'],
                'monthly_fee_range' => '2,000〜3,000円', 'has_parent_duty' => true,
                'policy_type' => 'バランス型', 'transfer_available' => false,
                'days_ago' => 14,
            ],
            [
                'title' => 'Twolaps Kids 駒沢公園',
                'search_query' => '駒沢オリンピック公園 陸上競技場',
                'address' => '東京都世田谷区駒沢公園1-1',
                'fallback_lat' => 35.62555, 'fallback_lng' => 139.66367,
                'category' => 'スポーツ少年団',
                'note' => '駒沢公園内で開催のかけっこ専門スクール。Google口コミ4.9の高評価。元実業団ランナーが正しいフォームを指導。1回完結型で気軽に参加できる。',
                'user_id' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1461896836934-bd45ba8bbd7e?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '4-12',
                'parent_role' => '送迎のみ（見学OK）',
                'category_tags' => ['かけっこ','駒沢公園','単発OK','運動会対策','高評価'],
                'monthly_fee_range' => '2,500円/回', 'has_parent_duty' => false,
                'policy_type' => '褒めて伸ばす', 'transfer_available' => true,
                'days_ago' => 10,
            ],
            [
                'title' => 'ディーバスイミング',
                'search_query' => 'DIVAスポーツクラブ 世田谷',
                'address' => '東京都世田谷区弦巻4丁目30-5',
                'fallback_lat' => 35.63759, 'fallback_lng' => 139.64249,
                'category' => '施設',
                'note' => '弦巻エリアの室内プール完備スイミングクラブ。キッズダンスやフィットネスも展開。ベビーから選手育成まで幅広いコース。水質管理が徹底されていて安心。',
                'user_id' => 3, 'ambassador_user_id' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1560089000-7433a4ebbd64?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '0-15',
                'parent_role' => '送迎のみ（見学ギャラリーあり）',
                'category_tags' => ['スイミング','室内プール','キッズダンス','ベビーOK','弦巻'],
                'monthly_fee_range' => '7,500〜10,000円', 'has_parent_duty' => false,
                'policy_type' => 'バランス型', 'transfer_available' => true,
                'days_ago' => 8,
            ],
            [
                'title' => 'フェルマー数学塾',
                'search_query' => null, // Googleに載っていない想定
                'address' => '東京都世田谷区弦巻1丁目28',
                'fallback_lat' => 35.63900, 'fallback_lng' => 139.65100,
                'category' => '塾・学習',
                'note' => '弦巻の住宅街にある数学専門塾。小中高対象。「なぜそうなるか」を追求する指導が特徴。少人数制で質問しやすい。中学受験対策にも対応。',
                'user_id' => 2, 'ambassador_user_id' => 6,
                'image_path' => 'https://images.unsplash.com/photo-1596495578065-6e0763fa1178?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '8-18',
                'parent_role' => '送迎のみ',
                'category_tags' => ['数学専門','少人数制','中学受験','弦巻','考える力'],
                'monthly_fee_range' => '15,000〜25,000円', 'has_parent_duty' => false,
                'policy_type' => '厳しく鍛える', 'transfer_available' => true,
                'days_ago' => 9,
            ],
            [
                'title' => 'くるみバレエスタジオ',
                'search_query' => null, // Googleに載っていない想定
                'address' => '東京都世田谷区桜新町1丁目',
                'fallback_lat' => 35.63350, 'fallback_lng' => 139.64600,
                'category' => '個人教室',
                'note' => '桜新町の老舗バレエスタジオ。幼児から成人まで対応。ロシアメソッド経験の講師が基礎を丁寧に指導。発表会の衣装は教室が手配してくれるので親の負担少なめ。',
                'user_id' => 3, 'ambassador_user_id' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1518834107812-67b0b7c58434?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '3-大人',
                'parent_role' => '送迎のみ（発表会時はお手伝いあり）',
                'category_tags' => ['バレエ','老舗','桜新町','ロシアメソッド','発表会あり'],
                'monthly_fee_range' => '8,000〜12,000円', 'has_parent_duty' => false,
                'policy_type' => 'バランス型', 'transfer_available' => true,
                'days_ago' => 15,
            ],
            // ===== 追加スポット =====
            [
                'title' => 'ヤマハ音楽教室 上町センター',
                'search_query' => 'ヤマハ音楽教室 上町 世田谷',
                'address' => '東京都世田谷区世田谷3丁目4-1',
                'fallback_lat' => 35.64520, 'fallback_lng' => 139.64850,
                'category' => '個人教室',
                'note' => '世田谷線上町駅すぐのヤマハ音楽教室。3歳からのおんがくなかよしコース、幼児科、ジュニア専門コースまで。グループレッスンで友達と楽しみながら音感が育つ。ピアノ・エレクトーン個人レッスンも選択可。駐輪場あり。',
                'user_id' => 1,
                'image_path' => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '3-15',
                'parent_role' => '幼児クラスは保護者同伴・小学生以降は送迎のみ',
                'category_tags' => ['ピアノ','音楽教室','ヤマハ','グループレッスン','上町'],
                'monthly_fee_range' => '7,000〜9,500円', 'has_parent_duty' => false,
                'policy_type' => '褒めて伸ばす', 'transfer_available' => true,
                'days_ago' => 6,
            ],
            [
                'title' => 'こども英語 WinBe 駒沢校',
                'search_query' => 'WinBe 駒沢 英語',
                'address' => '東京都世田谷区駒沢2丁目16-4',
                'fallback_lat' => 35.63100, 'fallback_lng' => 139.66150,
                'category' => '塾・学習',
                'note' => '駒沢大学駅徒歩3分の子ども英語スクール。ネイティブ講師と日本人講師のペアティーチングが特徴。フォニックス重視で英検対策にも強い。少人数クラスで発話の機会が多く、シャイな子でも自然に話せるようになると評判。',
                'user_id' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '3-12',
                'parent_role' => '送迎のみ（年2回の参観日あり）',
                'category_tags' => ['英語','英会話','ネイティブ','フォニックス','駒沢'],
                'monthly_fee_range' => '10,000〜16,000円', 'has_parent_duty' => false,
                'policy_type' => '褒めて伸ばす', 'transfer_available' => true,
                'days_ago' => 4,
            ],
            [
                'title' => '世田谷柔道クラブ',
                'search_query' => null,
                'address' => '東京都世田谷区弦巻2丁目25',
                'fallback_lat' => 35.63680, 'fallback_lng' => 139.65550,
                'category' => 'スポーツ少年団',
                'note' => '弦巻区民センター体育室で活動する柔道クラブ。水曜・土曜の週2回。元全日本ジュニア選手のOBコーチが指導。礼儀や挨拶を大切にし、心と体を鍛える方針。初心者は受身から丁寧に教わるので安心。区民大会で毎年入賞実績あり。',
                'user_id' => 3,
                'image_path' => 'https://images.unsplash.com/photo-1555597673-b21d5c935865?w=400&h=400&fit=crop',
                'link_url' => null, 'age_range' => '5-15',
                'parent_role' => '当番あり（月1回）・大会引率の手伝い',
                'category_tags' => ['柔道','武道','礼儀','弦巻','区民大会'],
                'monthly_fee_range' => '3,000〜4,000円', 'has_parent_duty' => true,
                'policy_type' => '厳しく鍛える', 'transfer_available' => false,
                'days_ago' => 7,
            ],
        ];

        foreach ($spotDefs as $i => $def) {
            // Try Places API → Geocoding → Fallback
            $resolved = null;
            if (!empty($def['search_query'])) {
                $resolved = $places->resolveLocation(
                    $def['search_query'],
                    $def['address'],
                    $def['fallback_lat'],
                    $def['fallback_lng']
                );
            } else {
                // No search query — try geocoding the address, fallback to manual
                $resolved = $places->resolveLocation(
                    $def['title'],
                    $def['address'],
                    $def['fallback_lat'],
                    $def['fallback_lng']
                );
            }

            $lat = $resolved['lat'] ?? $def['fallback_lat'];
            $lng = $resolved['lng'] ?? $def['fallback_lng'];
            $placeId = $resolved['place_id'] ?? null;

            $this->command->info(sprintf(
                "  [%d] %s → %s (%.6f, %.6f)",
                $i + 1,
                $def['title'],
                $placeId ? "Places API (place_id: {$placeId})" : ($lat !== $def['fallback_lat'] ? 'Geocoded' : 'Fallback'),
                $lat,
                $lng
            ));

            DB::table('spots')->insert([
                'id' => $i + 1,
                'title' => $def['title'],
                'lat' => $lat,
                'lng' => $lng,
                'category' => $def['category'],
                'note' => $def['note'],
                'user_id' => $def['user_id'],
                'ambassador_user_id' => $def['ambassador_user_id'] ?? null,
                'image_path' => $def['image_path'],
                'link_url' => $def['link_url'],
                'google_place_id' => $placeId,
                'age_range' => $def['age_range'],
                'parent_role' => $def['parent_role'],
                'category_tags' => json_encode($def['category_tags']),
                'monthly_fee_range' => $def['monthly_fee_range'],
                'has_parent_duty' => $def['has_parent_duty'],
                'policy_type' => $def['policy_type'],
                'transfer_available' => $def['transfer_available'],
                'created_at' => $now->copy()->subDays($def['days_ago']),
                'updated_at' => $now->copy()->subDays($def['days_ago']),
            ]);
        }

        // ========== Reviews ==========
        DB::table('reviews')->insert([
            ['spot_id'=>1,'user_id'=>3,'monthly_fee'=>2500,'parent_duty'=>true,'strictness'=>3,
             'body'=>'弦巻小の子がほとんどで友達と活動できるのが良い。当番は面倒だけど親同士の繋がりができる。コスパは最高、月2500円でこの指導は破格。',
             'satisfaction'=>4,'skill_growth'=>4,'cost_performance'=>5,'teacher_passion'=>4,'parent_burden'=>2,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(5),'updated_at'=>$now->copy()->subDays(5)],
            ['spot_id'=>1,'user_id'=>2,'monthly_fee'=>3000,'parent_duty'=>true,'strictness'=>3,
             'body'=>'小3息子が2年通ってます。試合経験が積めるのが大きい。ただ配車当番が年に数回あるのと、雨天時のグラウンド整備が地味に大変。',
             'satisfaction'=>4,'skill_growth'=>5,'cost_performance'=>5,'teacher_passion'=>3,'parent_burden'=>2,
             'vibe_tag'=>'ガチ勢','created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(3)],
            ['spot_id'=>2,'user_id'=>1,'monthly_fee'=>2500,'parent_duty'=>false,'strictness'=>2,
             'body'=>'運動会前に3回通わせたら走り方が見違えた！腕の振り方や着地を論理的に教えてくれる。送迎だけでいいので本当に楽。',
             'satisfaction'=>5,'skill_growth'=>5,'cost_performance'=>5,'teacher_passion'=>5,'parent_burden'=>5,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(4),'updated_at'=>$now->copy()->subDays(4)],
            ['spot_id'=>3,'user_id'=>2,'monthly_fee'=>8500,'parent_duty'=>false,'strictness'=>3,
             'body'=>'施設がキレイで水質も良い。コーチが若くて子どもウケ良い。キッズダンスとのセット割引もgood。振替も月2回までOK。',
             'satisfaction'=>4,'skill_growth'=>4,'cost_performance'=>3,'teacher_passion'=>4,'parent_burden'=>5,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(6),'updated_at'=>$now->copy()->subDays(6)],
            ['spot_id'=>3,'user_id'=>1,'monthly_fee'=>9000,'parent_duty'=>false,'strictness'=>3,
             'body'=>'ベビースイミングから通わせて3年。水を怖がらなくなったし、体力もついた。進級テストのたびにワッペンがもらえて本人やる気。',
             'satisfaction'=>5,'skill_growth'=>4,'cost_performance'=>3,'teacher_passion'=>4,'parent_burden'=>5,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
            ['spot_id'=>4,'user_id'=>1,'monthly_fee'=>20000,'parent_duty'=>false,'strictness'=>4,
             'body'=>'算数嫌いだった息子が「数学おもしろい」と言うように。パズルみたいに考えさせてくれる。月謝は高めだけど少人数で面倒見が良い。',
             'satisfaction'=>5,'skill_growth'=>5,'cost_performance'=>3,'teacher_passion'=>5,'parent_burden'=>5,
             'vibe_tag'=>'ガチ勢','created_at'=>$now->copy()->subDays(7),'updated_at'=>$now->copy()->subDays(7)],
            ['spot_id'=>5,'user_id'=>1,'monthly_fee'=>9000,'parent_duty'=>false,'strictness'=>3,
             'body'=>'3歳から通わせて姿勢がピンとしてきた。先生は厳しいけど愛がある。衣装は教室手配で親の負担少なめ。発表会では成長を実感。',
             'satisfaction'=>5,'skill_growth'=>4,'cost_performance'=>4,'teacher_passion'=>5,'parent_burden'=>4,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(8),'updated_at'=>$now->copy()->subDays(8)],
            ['spot_id'=>5,'user_id'=>3,'monthly_fee'=>10000,'parent_duty'=>false,'strictness'=>3,
             'body'=>'姉妹で通ってます。お姉ちゃんは6年目で、妹は2年目。先生が一人ひとりの個性を見てくれるのが嬉しい。',
             'satisfaction'=>5,'skill_growth'=>5,'cost_performance'=>4,'teacher_passion'=>5,'parent_burden'=>4,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            // ===== 追加スポットのレビュー =====
            ['spot_id'=>6,'user_id'=>2,'monthly_fee'=>8500,'parent_duty'=>false,'strictness'=>2,
             'body'=>'年中から通って2年。グループレッスンで友達と楽しそう。発表会もあって本人のモチベーションになってる。幼児クラスは親同伴だけど、その分成長が見られて嬉しい。',
             'satisfaction'=>4,'skill_growth'=>4,'cost_performance'=>3,'teacher_passion'=>4,'parent_burden'=>4,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(4),'updated_at'=>$now->copy()->subDays(4)],
            ['spot_id'=>6,'user_id'=>3,'monthly_fee'=>9500,'parent_duty'=>false,'strictness'=>2,
             'body'=>'ピアノ個人レッスンに切り替えました。先生が優しくて、練習嫌いだった娘も自分から弾くように。教材費が別途かかるのだけ注意。',
             'satisfaction'=>4,'skill_growth'=>3,'cost_performance'=>3,'teacher_passion'=>4,'parent_burden'=>5,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
            ['spot_id'=>7,'user_id'=>1,'monthly_fee'=>12000,'parent_duty'=>false,'strictness'=>2,
             'body'=>'ネイティブの先生がとにかく明るい！恥ずかしがり屋の息子が3ヶ月で「Hello!」から自己紹介まで言えるように。フォニックスのおかげで読む力もつき始めた。英検Jr.にも対応してくれる。',
             'satisfaction'=>5,'skill_growth'=>5,'cost_performance'=>3,'teacher_passion'=>5,'parent_burden'=>5,
             'vibe_tag'=>'エンジョイ勢','created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(3)],
            ['spot_id'=>8,'user_id'=>2,'monthly_fee'=>3500,'parent_duty'=>true,'strictness'=>4,
             'body'=>'礼儀と根性が身につく。「押忍！」の挨拶から始まり、受身→投げ技と段階的に教えてもらえる。コーチは厳しいけど子供のことをよく見てくれてる。区民大会で銅メダル獲れて息子は大喜び。当番は月1回で負担は少なめ。',
             'satisfaction'=>5,'skill_growth'=>5,'cost_performance'=>5,'teacher_passion'=>5,'parent_burden'=>3,
             'vibe_tag'=>'ガチ勢','created_at'=>$now->copy()->subDays(5),'updated_at'=>$now->copy()->subDays(5)],
        ]);

        // ========== Questions ==========
        DB::table('questions')->insert([
            ['id'=>1,'title'=>'スイミング教室の月謝相場ってどのくらい？','note'=>'弦巻エリアでスイミングを検討中です。週1回だと大体いくらくらいが相場なんでしょうか？兄弟割引とかある教室もあるのかな。','category'=>'月謝・費用','image_path'=>null,'lat'=>null,'lng'=>null,'user_id'=>3,'spot_id'=>3,'target_age'=>'年長','status'=>'open','created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(3)],
            ['id'=>2,'title'=>'少年団の当番ってどのくらい大変？','note'=>'サッカーの少年団に入れたいけど当番が不安。共働きでも続けられますか？実際に通わせてる方の声が聞きたいです。','category'=>'当番・親の負担','image_path'=>null,'lat'=>null,'lng'=>null,'user_id'=>2,'spot_id'=>1,'target_age'=>'小2','status'=>'open','created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
            ['id'=>3,'title'=>'バレエは何歳から始めるのがベスト？','note'=>'年少の娘がプリキュアの影響でバレエをやりたいと言い出しました。早すぎると体に負担がかかるとも聞くし…実際に通わせてる方、何歳から始めましたか？','category'=>'始め時・年齢','image_path'=>null,'lat'=>null,'lng'=>null,'user_id'=>1,'spot_id'=>5,'target_age'=>'年少','status'=>'open','created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            ['id'=>4,'title'=>'算数塾と公文、どちらが中学受験向き？','note'=>'小3の息子の中学受験を見据えて塾を探しています。公文で計算力をつけるか、フェルマー数学塾のような思考力系の塾に通うか迷っています。','category'=>'教室選び','image_path'=>null,'lat'=>null,'lng'=>null,'user_id'=>3,'spot_id'=>4,'target_age'=>'小3','status'=>'resolved','created_at'=>$now->copy()->subDays(5),'updated_at'=>$now->copy()->subDays(3)],
            ['id'=>5,'title'=>'習い事の送迎、みんなどうしてる？','note'=>'共働きで平日の送迎が厳しいです。ファミサポやシッターを使ってる方いますか？世田谷区の送迎サポート制度とかあれば教えてください。','category'=>'送迎・スケジュール','image_path'=>null,'lat'=>null,'lng'=>null,'user_id'=>2,'spot_id'=>null,'target_age'=>'小1','status'=>'open','created_at'=>$now->copy()->subHours(8),'updated_at'=>$now->copy()->subHours(8)],
            ['id'=>6,'title'=>'先生が厳しい教室、子どもは嫌がらない？','note'=>'体験に行った教室の先生がかなりストイックでした。子どもは「楽しかった」と言ってましたが、続けると嫌になるパターンも聞くし…「厳しく鍛える」系の教室に通わせてる方のリアルな感想が知りたいです。','category'=>'先生・指導','image_path'=>null,'lat'=>null,'lng'=>null,'user_id'=>1,'spot_id'=>null,'target_age'=>'小2','status'=>'open','created_at'=>$now->copy()->subHours(3),'updated_at'=>$now->copy()->subHours(3)],
        ]);

        // ========== Comments ==========
        DB::table('comments')->insert([
            // Q1: スイミング月謝
            ['id'=>1,'question_id'=>1,'user_id'=>1,'body'=>'ディーバスイミングに通わせてます。週1回で月8,500円です。兄弟割引で2人目以降500円引き。施設がキレイで満足してます。','thanks_count'=>5,'created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
            ['id'=>2,'question_id'=>1,'user_id'=>2,'body'=>'相場は7,000〜10,000円くらいだと思います。スクールバス付きだと+1,000円くらい。水着やゴーグルの初期費用も考えておくといいですよ。','thanks_count'=>3,'created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            // Q2: 少年団当番
            ['id'=>3,'question_id'=>2,'user_id'=>1,'body'=>'弦巻キッカーズに入ってます。当番は月2回でドリンク準備やライン引き。パパママで分担すればなんとかなります。むしろ親同士の繋がりができて良いですよ。','thanks_count'=>7,'created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            ['id'=>4,'question_id'=>2,'user_id'=>3,'body'=>'共働きです。正直最初は大変でしたが、LINEグループで当番シフトを調整してます。どうしても無理な時は他のパパに代わってもらえるので、思ったより柔軟。','thanks_count'=>4,'created_at'=>$now->copy()->subHours(18),'updated_at'=>$now->copy()->subHours(18)],
            // Q3: バレエ何歳から
            ['id'=>5,'question_id'=>3,'user_id'=>3,'body'=>'くるみバレエに3歳から通わせてます。最初はリズム遊びが中心で、本格的なレッスンは年長くらいから。早く始めても身体への負担は少ないと先生に言われました。','thanks_count'=>6,'created_at'=>$now->copy()->subHours(20),'updated_at'=>$now->copy()->subHours(20)],
            // Q4: 算数塾 vs 公文（解決済み）
            ['id'=>6,'question_id'=>4,'user_id'=>2,'body'=>'両方通わせた経験があります。公文は計算スピードがつきますが、文章題や図形は弱い。思考力を伸ばしたいならフェルマーのような塾がおすすめ。うちは公文→フェルマーに切り替えました。','thanks_count'=>8,'created_at'=>$now->copy()->subDays(4),'updated_at'=>$now->copy()->subDays(4)],
            ['id'=>7,'question_id'=>4,'user_id'=>1,'body'=>'フェルマー数学塾に通わせてます。「なんでそうなるの？」を徹底的に考えさせてくれるので、算数嫌いだった息子が「数学おもしろい」って言うように。月謝は高めだけど少人数で手厚いです。','thanks_count'=>5,'created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(3)],
            // Q5: 送迎
            ['id'=>8,'question_id'=>5,'user_id'=>3,'body'=>'世田谷区のファミサポ使ってます。1時間800円で送迎してもらえます。事前に登録と面談が必要ですが、同じ方に継続でお願いできるので子どもも慣れてます。','thanks_count'=>3,'created_at'=>$now->copy()->subHours(5),'updated_at'=>$now->copy()->subHours(5)],
            // Q6: 厳しい先生
            ['id'=>9,'question_id'=>6,'user_id'=>2,'body'=>'うちの子は「厳しいけど面白い」先生が好きです。大事なのは厳しさの中に愛情があるかどうか。体験の時にお子さんの反応をよく見てあげてください。','thanks_count'=>2,'created_at'=>$now->copy()->subHours(1),'updated_at'=>$now->copy()->subHours(1)],
        ]);

        // ========== Thanks ==========
        DB::table('thanks')->insert([
            ['user_id'=>3,'comment_id'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['user_id'=>2,'comment_id'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['user_id'=>1,'comment_id'=>2,'created_at'=>$now,'updated_at'=>$now],
            ['user_id'=>3,'comment_id'=>2,'created_at'=>$now,'updated_at'=>$now],
        ]);

        // ========== Ambassador Posts ==========
        $ambassadorPosts = [
            // ディーバスイミング (spot_id=3, user_id=4)
            [
                'user_id' => 4, 'spot_id' => 3,
                'photo_path' => 'https://images.unsplash.com/photo-1600965962102-9d260a71890d?w=600&h=450&fit=crop',
                'message' => '今日のキッズクラスは満員御礼！みんな顔つきを水につける練習を頑張りました。最初は泣いていた子も、最後は笑顔でバタ足。この瞬間がたまりません。',
                'mood_tag' => 'レッスン風景',
                'created_at' => $now->copy()->subHours(3),
            ],
            [
                'user_id' => 4, 'spot_id' => 3,
                'photo_path' => 'https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?w=600&h=450&fit=crop',
                'message' => '年中さんのはるくん、ついに25m完泳！入会から8ヶ月、コツコツ頑張った成果です。お母さんも涙ぐんでいました。',
                'mood_tag' => '生徒の成長',
                'created_at' => $now->copy()->subDays(2),
            ],
            [
                'user_id' => 4, 'spot_id' => 3,
                'photo_path' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=450&fit=crop',
                'message' => '卒業生のご家族から水着とゴーグルをいただきました！サイズが合う生徒さんにお譲りします。きれいな状態です。',
                'mood_tag' => 'お知らせ',
                'has_osagari' => true,
                'osagari_item' => '水着・ゴーグルセット',
                'osagari_size' => '130cm・ジュニアM',
                'created_at' => $now->copy()->subHours(5),
            ],
            // くるみバレエスタジオ (spot_id=5, user_id=5)
            [
                'user_id' => 5, 'spot_id' => 5,
                'photo_path' => 'https://images.unsplash.com/photo-1518834107812-67b0b7c58434?w=600&h=450&fit=crop',
                'message' => '春の発表会に向けて、年長クラスの振付が仕上がってきました。真剣な眼差しがたまらなくかわいい。本番が楽しみです！',
                'mood_tag' => 'レッスン風景',
                'created_at' => $now->copy()->subHours(6),
            ],
            [
                'user_id' => 5, 'spot_id' => 5,
                'photo_path' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=600&h=450&fit=crop',
                'message' => 'スタジオの窓から差し込む夕日が綺麗で思わずパシャリ。この空間で踊る子どもたちは本当に幸せそう。',
                'mood_tag' => '教室の雰囲気',
                'created_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id' => 5, 'spot_id' => 5,
                'photo_path' => 'https://images.unsplash.com/photo-1535525153412-5a42439a210d?w=600&h=450&fit=crop',
                'message' => '発表会で使ったチュチュをお下がりに出します！2回着用のみ、クリーニング済み。次の小さなバレリーナへ。',
                'mood_tag' => 'お知らせ',
                'has_osagari' => true,
                'osagari_item' => 'バレエチュチュ（白）',
                'osagari_size' => '110cm・美品',
                'created_at' => $now->copy()->subHours(10),
            ],
            // フェルマー数学塾 (spot_id=4, user_id=6)
            [
                'user_id' => 6, 'spot_id' => 4,
                'photo_path' => 'https://images.unsplash.com/photo-1596495578065-6e0763fa1178?w=600&h=450&fit=crop',
                'message' => '小5クラス、今日は「場合の数」に挑戦。最初は「わかんない！」連発だったけど、樹形図を描いたら「あ！そういうことか！」の声が教室に響きました。',
                'mood_tag' => 'レッスン風景',
                'created_at' => $now->copy()->subHours(8),
            ],
            [
                'user_id' => 6, 'spot_id' => 4,
                'photo_path' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=450&fit=crop',
                'message' => '春期講習の募集を開始しました。今年は「考える力を鍛える5日間」をテーマに、算数パズルや理科実験も交えた特別プログラムです。',
                'mood_tag' => 'お知らせ',
                'created_at' => $now->copy()->subDays(3),
            ],
        ];

        foreach ($ambassadorPosts as $post) {
            DB::table('ambassador_posts')->insert(array_merge([
                'has_osagari' => false,
                'osagari_item' => null,
                'osagari_size' => null,
            ], $post, [
                'updated_at' => $post['created_at'],
            ]));
        }

        // ========== My School & Favorites (テストデータ) ==========
        // user_id=1 の拠点校を弦巻小学校（id=3）に設定
        $tsurumaSchool = \App\Models\School::where('name', '弦巻小学校')->first();
        if ($tsurumaSchool) {
            DB::table('users')->where('id', 1)->update(['my_school_id' => $tsurumaSchool->id]);
        }
        // user_id=2 の拠点校を駒沢小学校（id=4）に設定
        $komazawaSchool = \App\Models\School::where('name', '駒沢小学校')->first();
        if ($komazawaSchool) {
            DB::table('users')->where('id', 2)->update(['my_school_id' => $komazawaSchool->id]);
        }

        // ========== Ambassador Q&A ==========
        DB::table('ambassador_questions')->insert([
            ['id'=>1,'ambassador_user_id'=>4,'user_id'=>1,'body'=>'ベビースイミングは何ヶ月から参加できますか？','created_at'=>$now->copy()->subDays(4),'updated_at'=>$now->copy()->subDays(4)],
            ['id'=>2,'ambassador_user_id'=>4,'user_id'=>3,'body'=>'振替は月に何回まで可能ですか？','created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
            ['id'=>3,'ambassador_user_id'=>5,'user_id'=>1,'body'=>'年少の娘ですが、体が硬くても大丈夫ですか？','created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(3)],
            ['id'=>4,'ambassador_user_id'=>5,'user_id'=>2,'body'=>'発表会の衣装代はどのくらいかかりますか？','created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            ['id'=>5,'ambassador_user_id'=>6,'user_id'=>3,'body'=>'中学受験を考えていますが、小3からでも間に合いますか？','created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
        ]);

        DB::table('ambassador_answers')->insert([
            ['id'=>1,'ambassador_question_id'=>1,'user_id'=>4,'body'=>'6ヶ月から参加できます！首が座っていれば大丈夫です。最初はお母さんと一緒にプールに入りますので、水着をご持参ください。','created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(3)],
            ['id'=>2,'ambassador_question_id'=>2,'user_id'=>4,'body'=>'月2回まで振替可能です。前日までにお電話いただければ対応いたします。','created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            ['id'=>3,'ambassador_question_id'=>3,'user_id'=>5,'body'=>'もちろん大丈夫ですよ！幼児クラスでは柔軟性を高めるストレッチから始めます。むしろ小さいうちから始めた方が体が柔らかくなりやすいです。','created_at'=>$now->copy()->subDays(2),'updated_at'=>$now->copy()->subDays(2)],
            ['id'=>4,'ambassador_question_id'=>4,'user_id'=>5,'body'=>'衣装は教室で一括手配しますので、1着3,000〜5,000円程度です。レンタルもご用意しています。','created_at'=>$now->copy()->subHours(18),'updated_at'=>$now->copy()->subHours(18)],
            ['id'=>5,'ambassador_question_id'=>5,'user_id'=>6,'body'=>'小3からでも十分間に合います。むしろ思考力を育てるには早すぎない良いタイミングです。まずは体験授業にお越しください！','created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
        ]);

        // ========== Trial Requests ==========
        DB::table('trial_requests')->insert([
            ['ambassador_user_id'=>4,'spot_id'=>3,'user_id'=>1,'parent_name'=>'田中 美咲','child_name'=>'田中 ゆい','child_age'=>'年長（6歳）','phone'=>'090-1234-5678','email'=>'misaki@example.com','note'=>'水を少し怖がるので、慣れるところから始めたいです。','status'=>'contacted','created_at'=>$now->copy()->subDays(3),'updated_at'=>$now->copy()->subDays(2)],
            ['ambassador_user_id'=>5,'spot_id'=>5,'user_id'=>3,'parent_name'=>'鈴木 あゆみ','child_name'=>'鈴木 さくら','child_age'=>'年少（4歳）','phone'=>'090-9876-5432','email'=>null,'note'=>null,'status'=>'pending','created_at'=>$now->copy()->subDays(1),'updated_at'=>$now->copy()->subDays(1)],
            ['ambassador_user_id'=>6,'spot_id'=>4,'user_id'=>2,'parent_name'=>'佐藤 健太','child_name'=>'佐藤 りく','child_age'=>'小3（9歳）','phone'=>'080-1111-2222','email'=>'kenta@example.com','note'=>'中学受験を検討中です。算数の苦手を克服したいです。','status'=>'pending','created_at'=>$now->copy()->subHours(12),'updated_at'=>$now->copy()->subHours(12)],
        ]);

        // お気に入り
        DB::table('favorites')->insert([
            ['user_id' => 1, 'spot_id' => 3, 'created_at' => $now, 'updated_at' => $now], // ディーバスイミング
            ['user_id' => 1, 'spot_id' => 5, 'created_at' => $now, 'updated_at' => $now], // くるみバレエ
            ['user_id' => 1, 'spot_id' => 4, 'created_at' => $now, 'updated_at' => $now], // フェルマー数学塾
            ['user_id' => 2, 'spot_id' => 1, 'created_at' => $now, 'updated_at' => $now], // 弦巻キッカーズ
            ['user_id' => 2, 'spot_id' => 2, 'created_at' => $now, 'updated_at' => $now], // Twolaps
        ]);
    }
}
