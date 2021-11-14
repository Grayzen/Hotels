<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use AmrShawky\LaravelCurrency\Facade\Currency;
// use AmrShawky\Currency;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hotelRoomsLeft = [];
        // Filtreleme ve son seçimlerin kalması
        if (isset($request->starting_at, $request->ending_at)) {
            $hotels = Hotel::filter()->get();
            foreach ($hotels as $key => $hotel) {
                $hotelRentedRooms = DB::table('user_hotel')
                    ->where('hotel_id', $hotel->id)
                    ->where('starting_at', '<', $request->starting_at)
                    ->where('ending_at', '>', $request->ending_at)
                    ->count();
                $hotelRoomsLeft[] = $hotel->total_rooms - $hotelRentedRooms;
            }
            session()->put('city', $request->address);
            session()->put('starting_at', $request->starting_at);
            session()->put('ending_at', $request->ending_at);
            // dd($hotelRoomsLeft);
        } else
            $hotels = [];


        $curl = curl_init();
        // Her bir otelin dolar kurunu türk lirasına çevirme
        foreach ($hotels as $key => $hotel) {
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://fixer-fixer-currency-v1.p.rapidapi.com/convert?from=USD&to=TRY&amount=" . $hotel->room_price,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: fixer-fixer-currency-v1.p.rapidapi.com",
                    "x-rapidapi-key: 001f405865msh4d95b171fc1bc26p1554e6jsne4f49bee38e8"
                ],
            ]);

            $response = curl_exec($curl);

            $response = json_decode($response);
            $hotel['try_price'] = $response->result;
        }
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            echo "cURL Error #:" . $err;
        }
        // else {
        // echo $response;
        // dd($response->result);
        // }

        return view('hotels.index', compact('hotels', 'hotelRoomsLeft'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hotels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:55|unique:hotels,name',
            'address' => 'required|string',
            'total_rooms' => 'required|integer',
            'stars' => 'required|integer|max:5',
            'room_price' => 'required|integer|max:999999',
            'image' => 'required|max:1024|image',
        ]);

        $imageName = time() . '_' . $request->image->getClientOriginalName();

        $request->image->move(public_path('images'), $imageName);
        // Yeni otel kaydı
        $hotel = Hotel::create([
            'name' => $request->name,
            'address' => $request->address,
            'total_rooms' => $request->total_rooms,
            'stars' => $request->stars,
            'room_price' => $request->room_price,
            'image' => $imageName,
        ]);

        $status = "Hotel " . $request->name . " Created";

        return view('hotels.create', compact('status'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function show(Hotel $hotel)
    {
        $curl = curl_init();
        // Seçilen otelin dolar kurunu türk lirasına çevirme
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://fixer-fixer-currency-v1.p.rapidapi.com/convert?from=USD&to=TRY&amount=" . $hotel->room_price,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: fixer-fixer-currency-v1.p.rapidapi.com",
                "x-rapidapi-key: 001f405865msh4d95b171fc1bc26p1554e6jsne4f49bee38e8"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        // else {
        // echo $response;
        // dd($response->result);
        // }
        $hotel['try_price'] = $response->result;

        // Kalan oda sayısını gösterme
        $hotelRentedRooms = DB::table('user_hotel')
            ->where('hotel_id', $hotel->id)
            ->where('starting_at', '<', session('starting_at'))
            ->where('ending_at', '>', session('ending_at'))
            ->count();
        $hotelRoomsLeft = $hotel->total_rooms - $hotelRentedRooms;

        return view('hotels.show', compact('hotel', 'hotelRoomsLeft'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotel $hotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        //
    }

    // public function search(Type $var = null)
    // {
    //     # code...
    // }

    public function rent(Request $request)
    {
        $request->validate([
            'starting_at' => 'required|date',
            'ending_at' => 'required|date'
        ]);

        // Oda kalmadıysa hata mesajı
        if ($request->hotelRoomsLeft < 1)
            return redirect()->back()->with('status', 'Sorry, No Rooms Left');

        // Kullanıcı otel rezervasyon kaydı
        $userHotel = DB::table('user_hotel')->insert([
            'user_id' => Auth::user()->id,
            'hotel_id' => $request->hotel_id,
            'starting_at' => $request->starting_at,
            'ending_at' => $request->ending_at,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('hotels.index')->with('status', 'Reservation Successful!');
    }
}
