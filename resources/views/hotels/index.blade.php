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

    <div class="container py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @isset($status)
                        <x-label for="status" value="{{ $status }}" class="text-green-600" />
                    @endisset
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    Search
                    <form action="{{ route('hotels.index') }}" method="GET" class="mt-2">
                        <select name="address" id="address">
                            <option value="">Choose City</option>
                            <option value="Ankara" @if (session('city') == 'Ankara') selected @endif>Ankara</option>
                            <option value="Istanbul" @if (session('city') == 'Istanbul') selected @endif>Istanbul</option>
                            <option value="Izmir" @if (session('city') == 'Izmir') selected @endif>Izmir</option>
                            <option value="Mersin" @if (session('city') == 'Mersin') selected @endif>Mersin</option>
                        </select>
                        <x-input type="date" name="starting_at" class="ml-2" placeholder="Starting Date"
                            value="{{ session('starting_at') ? session('starting_at') : '' }}" />
                        <x-input type="date" name="ending_at" class="ml-2" placeholder="Ending Date"
                            value="{{ session('ending_at') ? session('ending_at') : '' }}" />
                        <x-button type="submit" class="ml-2">Search</x-button>
                    </form>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse ($hotels as $key => $hotel)
                        <a href="{{ route('hotels.show', $hotel->id) }}">
                            <div class="mx-auto flex">
                                <div class=" items-stretch grid grid-cols-3 gap-4">
                                    <div class="flex-1 p-4">
                                        <div class="block bg-white overflow-hidden border-2 h-full">
                                            <div class="p-4">
                                                <h3 class="mt-2 mb-2 font-bold text-sm">
                                                    {{ $hotel->name }}
                                                </h3>
                                                <img src="images/{{ $hotel->image }}" alt="" width="200px">
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
                                            </div>
                                            @if (count($hotelRoomsLeft))
                                                <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                    @if ($hotelRoomsLeft[$key] < 15)
                                                        <x-label for="rooms_left"
                                                            value="Rooms Left: {{ $hotelRoomsLeft[$key] }}"
                                                            class="text-red-400" />
                                                    @else
                                                        <x-label for="rooms_left"
                                                            value="Rooms Left: {{ $hotelRoomsLeft[$key] }}"
                                                            class="text-green-500" />
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                <x-label for="price" value="Room Price: {{ $hotel->room_price }}$" />
                                            </div>
                                            @isset($hotel->try_price)
                                                <div class="pl-4 pb-3 flex flex-wrap items-center">
                                                    <x-label for="try_price"
                                                        value="Room Price TRY: {{ number_format($hotel->try_price) }}₺" />
                                                </div>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        Please search for City and Dates.
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    // function ch() {
    //     alert(document.getElementById('min'));
    // }
</script>
