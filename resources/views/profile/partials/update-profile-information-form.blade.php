<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            
            <div class="flex items-center gap-6 mt-2 mb-4">
                <div class="text-center">
                    <span class="block text-xs text-gray-500 mb-1 font-medium">Current Picture</span>
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 shadow-sm bg-gray-50 flex items-center justify-center">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('default-avatar.png') }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                </div>

                <div id="preview-container" class="hidden text-center">
                    <span class="block text-xs text-indigo-600 font-semibold mb-1">New Selection</span>
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-indigo-500 shadow-md bg-gray-50 flex items-center justify-center">
                        <img id="image-preview" src="#" alt="New Preview" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <input type="file" name="profile_picture" id="profile_picture" 
                   accept="image/png, image/jpeg, image/jpg, image/gif"
                   class="mt-1 block w-full text-sm text-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show={show}
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('profile_picture').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                const fileType = file['type'];
                const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'];
                
                if (!validImageTypes.includes(fileType)) {
                    alert('Only JPG, JPEG, PNG or GIF images are allowed!');
                    this.value = ''; 
                    document.getElementById('preview-container').classList.add('hidden');
                    return;
                }

                document.getElementById('image-preview').src = URL.createObjectURL(file);
                document.getElementById('preview-container').classList.remove('hidden');
            }
        }
    </script>
</section>