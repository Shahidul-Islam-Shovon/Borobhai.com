<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile Settings') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('status') === 'profile-updated')
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-md shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.297a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Profile updated successfully! </span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg text-center border border-gray-200">
                    <div class="relative w-28 h-28 mx-auto rounded-full overflow-hidden border-4 border-indigo-500/20 bg-gray-50 flex items-center justify-center">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('default-avatar.png') }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    
                    <h3 class="mt-4 text-lg font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ auth()->user()->email }}</p>
                    
                    <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full mx-auto uppercase tracking-wider">
                            @if(auth()->user()->is_super_admin)
                                Super Admin
                            @elseif(auth()->user()->role === 'admin')
                                Admin
                            @else
                                {{ auth()->user()->role }}
                            @endif
                        </span>
                        <span class="text-xs text-gray-400 mt-1">Joined: {{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    @if(!auth()->user()->is_super_admin && auth()->user()->role !== 'admin')
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-red-200 bg-gradient-to-r from-white to-red-50/10">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>