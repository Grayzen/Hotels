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
                    @isset($status)
                        <x-label for="status" value="{{ $status }}" class="text-green-600" />
                    @endisset
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <x-label for="name" :value="__('Name')" />
                        <x-input type="text" name="name" placeholder="Hotel Name" />
                        <br>
                        <x-label for="address" :value="__('City')" class="mt-4" />
                        <x-input type="text" name="address" placeholder="Hotel City" />
                        <br>
                        <x-label for="total_rooms" :value="__('Total Rooms')" class="mt-4" />
                        <x-input type="text" name="total_rooms" placeholder="Total Rooms" />
                        <br>
                        <x-label for="stars" :value="__('Stars')" class="mt-4" />
                        <x-input type="text" name="stars" placeholder="Hotel Stars" />
                        <br>
                        <x-label for="room_price" :value="__('Room Price')" class="mt-4" />
                        <x-input type="text" name="room_price" placeholder="Room Price $$" />
                        <br>
                        <x-label for="desc" :value="__('Description')" class="mt-4" />
                        <textarea type="text" name="desc" placeholder="Hotel Description"></textarea>
                        <br>
                        <x-label for="image" :value="__('Hotel Image')" class="mt-4" />
                        <x-input type="file" name="image" placeholder="Hotel Image" />
                        <br>
                        <x-button type="submit" class="mt-4">Create</x-button>

                        {{-- <x-label for="total_rooms" :value="__('Total Rooms')" />
                        <x-input id="total_rooms" class="block mt-1 w-full" type="text" name="total_rooms"
                            :value="old('total_rooms')" required autofocus /> --}}

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
