<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('hotels.index')" :active="request()->routeIs('hotels.index')">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hotels') }}
            </h2>
        </x-nav-link>

        <x-nav-link :href="route('hotels.create')" :active="request()->routeIs('hotels.create')">
            {{ __('Create') }}
        </x-nav-link>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto p-6">
                        <div class="mx-auto flex">
                            <div class=" items-stretch grid grid-cols-3 gap-4">
                                <div class="flex-1 p-4">
                                    <form action="{{ route('hotels.rent') }}" method="post">
                                        @csrf
                                        <div class="block bg-white overflow-hidden border-2 h-full">
                                            <div class="p-4">
                                                <h3 class="mt-2 mb-2 font-bold text-sm">
                                                    {{ $hotel->name }}
                                                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                </h3>
                                                <img src="{{ asset('images/' . $hotel->image) }}" alt=""
                                                    width="200px">
                                                <div class="mt-2 flex flex-wrap">
                                                    {{ $hotel->address }}
                                                </div>
                                                <div class="pt-3 text-md text-justify flex flex-wrap break-normal">
                                                    {{ $hotel->desc }}
                                                </div>
                                                <div class="pt-3 text-md text-justify">
                                                    @for ($i = 0; $i < $hotel->stars; $i++)
                                                        <div class="clip-star"></div>
                                                    @endfor
                                                </div>
                                                <div class="pt-3 text-md text-justify">
                                                    <input type="date" value="{{ session('starting_at') }}" disabled>
                                                    <input type="hidden" name="starting_at"
                                                        value="{{ session('starting_at') }}">
                                                </div>
                                                <div class="pt-3 text-md text-justify">
                                                    <input type="date" value="{{ session('ending_at') }}" disabled>
                                                    <input type="hidden" name="ending_at"
                                                        value="{{ session('ending_at') }}">
                                                </div>
                                            </div>
                                            <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                @if ($hotelRoomsLeft < 15)
                                                    <x-label for="rooms_left"
                                                        value="Rooms Left: {{ $hotelRoomsLeft }}"
                                                        class="text-red-400" />
                                                    <input type="hidden" name="hotelRoomsLeft"
                                                        value="{{ $hotelRoomsLeft }}">
                                                @else
                                                    <x-label for="rooms_left"
                                                        value="Rooms Left: {{ $hotelRoomsLeft }}"
                                                        class="text-green-500" />
                                                    <input type="hidden" name="hotelRoomsLeft"
                                                        value="{{ $hotelRoomsLeft }}">
                                                @endif
                                            </div>
                                            <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                <x-label id="price" for="price"
                                                    value="Room Price: {{ $hotel->room_price }}$" />
                                            </div>
                                            <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                <x-label id="try_price" for="try_price"
                                                    value="Room Price TRY: {{ number_format($hotel->try_price) }}₺" />
                                            </div>
                                            <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                {{-- <x-button onclick="down()">-</x-button> --}}
                                                <x-label for="person" value="Person: " />
                                                <x-input id="person" type="number" min="1" max="5"
                                                    class="ml-2" value="1" width="10px"
                                                    onchange="priceChange(this.value)" />
                                                {{-- <x-button onclick="up()">+</x-button> --}}
                                                <x-button type="submit" class="ml-3">Rent</x-button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function priceChange(value) {
        document.getElementById('price').innerText = 'Room Price: ' + value * {{ $hotel->room_price }} + '$';
        document.getElementById('try_price').innerText = 'Room Price TRY: ' + (value *
            {{ $hotel->try_price }}).toFixed(0) + '₺';
    }
</script>
