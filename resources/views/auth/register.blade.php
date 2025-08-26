<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
        </div>

        <!-- Middle Name -->
        <div class="mt-4">
            <x-input-label for="middlename" :value="__('Middle Name')" />
            <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename')" />
            <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="lastname" :value="__('Last Name')" />
            <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autocomplete="lastname" />
            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Profile Picture -->
        <div class="mt-4">
            <x-input-label for="photo" :value="__('Profile Picture')" />
            <input id="photo" type="file" name="photo" accept="image/*">
            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
        </div>

        <!-- Skill Category (for filtering only, not submitted) -->
        <div class="mt-4">
            <x-input-label for="skill_category" :value="__('Skill Category')" />
            <select id="skill_category" class="block mt-1 w-full" required>
                <option value="">Select a category</option>
                @foreach($skills->groupBy('category') as $category => $group)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <!-- Skill Name (this is submitted) -->
        <div class="mt-4">
            <x-input-label for="skill_id" :value="__('Skill Name')" />
            <select id="skill_id" name="skill_id" class="block mt-1 w-full" required>
                <option value="">Select a skill</option>
                @foreach($skills as $skill)
                    <option value="{{ $skill->skill_id }}" data-category="{{ $skill->category }}">{{ $skill->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('skill_id')" class="mt-2" />
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('skill_category');
            const skillSelect = document.getElementById('skill_id');
            const allOptions = Array.from(skillSelect.options);

            categorySelect.addEventListener('change', function() {
                const selectedCategory = this.value;
                skillSelect.innerHTML = '<option value="">Select a skill</option>';
                allOptions.forEach(option => {
                    if (!option.value) return; // skip placeholder
                    if (option.getAttribute('data-category') === selectedCategory) {
                        skillSelect.appendChild(option.cloneNode(true));
                    }
                });
            });
        });
        </script>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
