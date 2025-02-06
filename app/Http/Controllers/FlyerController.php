<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use App\Models\FlyerView;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FlyerController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $flyers = Flyer::where('user_id', auth()->id())->where('title', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('flyers.index', compact('flyers', 'filter'));
    }

    public function add(){
        return view('flyers.add');
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'periode' => 'required|date',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'open_seat' => 'required|numeric',
            'left_seat' => 'required|numeric|lte:open_seat',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store(auth()->id() . '/flyers', 'public');
            } else {
                return redirect()->back()->with('error', 'Image is required'); 
            }

            $flyer = new Flyer();
            $flyer->image = $imagePath;
            $flyer->user_id = auth()->id();
            $flyer->title = $request->get('title');
            $flyer->description = $request->get('description');
            $flyer->periode = $request->get('periode');
            $flyer->open_seat = $request->get('open_seat');
            $flyer->left_seat = $request->get('left_seat');
            $flyer->save();

            return redirect()->route('flyer.index')->with('success', $flyer->title.' berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat flyer, Error : '.$e->getMessage());
        }
    }

    public function edit($id){
        $flyer = Flyer::findOrFail($id);
        return view('flyers.edit', compact('flyer'));
    }
    
    public function update($id, Request $request){

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',  
            'periode' => 'required|date',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'open_seat' => 'required|numeric',
            'left_seat' => 'required|numeric|lte:open_seat', 
        ]);
        try {
            $flyer = Flyer::findOrFail($id);
            if($flyer->md->id != auth()->user()->id) {
                return redirect()->route('home')->with('error', 'JANGAN MELAKUKAN HAL BURUK, INI BUKAN FLYER ANDA!! SISTEM MENCATAT KELAKUAN ANDA');
            }

            if ($request->hasFile('image')) {
                if ($flyer->image && file_exists(storage_path('app/public/' . $flyer->image))) {
                    unlink(storage_path('app/public/' . $flyer->image));
                }

                $imagePath = $request->file('image')->store(auth()->id() . '/flyers', 'public');
                $flyer->image = $imagePath;
            }

            $flyer->title = $request->get('title');
            $flyer->description = $request->get('description');
            $flyer->periode = $request->get('periode');
            $flyer->open_seat = $request->get('open_seat');
            $flyer->left_seat = $request->get('left_seat');
            $flyer->save();

            return redirect()->route('flyer.index')->with('success', $flyer->title . ' berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui flyer, Error : ' . $e->getMessage());
        }
    }

    public function seat($id, Request $request){
        $request->validate([
            'open_seat' => 'required|numeric',
            'left_seat' => 'required|numeric|lte:open_seat', 
        ]);

        try {
            $flyer = Flyer::findOrFail($id);

            $flyer->open_seat = $request->get('open_seat');
            $flyer->left_seat = $request->get('left_seat');
            $flyer->save();

            return redirect()->route('flyer.index')->with('success', $flyer->title . ' sisa seat berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui sisa seat flyer, Error : ' . $e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $flyer = Flyer::findOrFail($id);
            if($flyer->md->id != auth()->user()->id) {
                return redirect()->route('home')->with('error', 'JANGAN MELAKUKAN HAL BURUK, INI BUKAN FLYER ANDA!! SISTEM MENCATAT KELAKUAN ANDA');
            }
            $flyer->delete();
            
            return redirect()->back()->with('success', 'Flyer Berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus flyer, Error : ' . $e->getMessage());
        }
    }

    //---------------------
    //------------ Status
    //---------------------
    public function status($id)
    {
        $flyer = Flyer::where('id', $id)->firstOrFail();
        $ip = request()->ip();

        $location = Cache::remember("location_{$ip}", now()->addDay(), function () use ($ip) {
            $apiKey = env('API_GEOLOCATION'); 
            $response = Http::get("https://api.ipgeolocation.io/ipgeo?apiKey={$apiKey}&ip={$ip}");

            $location = 'Unknown';

            if ($response->successful()) {
                $data = $response->json();
                $city = $data['city'] ?? null;
                $country = $data['country_name'] ?? null;

                if ($city && $country) {
                    $location = "{$city}, {$country}";
                } elseif ($country) {
                    $location = $country;
                }
            }

            return $location;
        });

        $cacheKey = "viewed_{$ip}_flyer_{$id}_" . now()->toDateString();

        if (!Cache::has($cacheKey)) {
            FlyerView::create([
                'flyer_id' => $id,
                'location' => $location,
            ]);

            Cache::put($cacheKey, true, now()->endOfDay());
        }

        return view('flyers.status', compact('flyer'));
    }


    public function qrWithLogo($id)
    {
        try {
            $flyer = Flyer::findOrFail($id);
            $url = route('flyer.status', ['id' => $id]);

            $logoPath = public_path('assets/images/logos/tanurqr.png'); // Ganti dengan path logo Anda
            $qrCodeResult = Builder::create()
                ->writer(new PngWriter())
                ->data($url)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                ->logoPath($logoPath)
                ->logoResizeToWidth(50)
                ->build();

            // Simpan QR Code ke storage sementara
            $fileName = 'qr_code_' . $id . '.png';
            Storage::disk('public')->put('qrcodes/' . $fileName, $qrCodeResult->getString());

            // URL untuk preview di modal
            $qrCodeUrl = Storage::url('qrcodes/' . $fileName);

            return response()->json([
                'qrCodeUrl' => $qrCodeUrl
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR Code: ' . $e->getMessage()], 500);
        }
    }
}
