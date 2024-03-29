<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-jet-welcome>
                    <x-slot name="welcome_message">
                        <div class="mt-8 text-2xl">
                            Welcome to Customer Dashboard! 
                        </div>
                    </x-slot>
                    <x-slot name="content">
                    
                    </x-slot>
                </x-jet-welcome>
            </div>
        </div>
    </div>
</x-app-layout>
