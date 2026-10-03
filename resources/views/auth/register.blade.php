<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6 max-w-2xl mx-auto py-4">
        @csrf

        <h2 class="text-2xl font-bold text-gray-800 text-center border-b pb-3">CBO Membership Registration</h2>

        <!-- 1. PERSONAL INFORMATION -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-3">1. Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" :value="__('Full Name *')" />
                    <x-text-input id="name" class="block mt-1 w-full text-sm" type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="date_of_birth" :value="__('Date of Birth *')" />
                    <x-text-input id="date_of_birth" class="block mt-1 w-full text-sm" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="id_number" :value="__('National ID Number *')" />
                    <x-text-input id="id_number" class="block mt-1 w-full text-sm" type="text" name="id_number" :value="old('id_number')" required />
                    <x-input-error :messages="$errors->get('id_number')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="physical_address" :value="__('Physical Address / Village *')" />
                    <x-text-input id="physical_address" class="block mt-1 w-full text-sm" type="text" name="physical_address" :value="old('physical_address')" required />
                    <x-input-error :messages="$errors->get('physical_address')" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- 2. CONTACT DETAILS -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-3">2. Contact Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="phone_number" :value="__('Primary Phone Number *')" />
                    <x-text-input id="phone_number" class="block mt-1 w-full text-sm" type="text" name="phone_number" :value="old('phone_number')" required />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="whatsapp_number" :value="__('WhatsApp Number')" />
                    <x-text-input id="whatsapp_number" class="block mt-1 w-full text-sm" type="text" name="whatsapp_number" :value="old('whatsapp_number')" />
                    <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="email" :value="__('Email Address *')" />
                    <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="next_of_kin_name" :value="__('Next of Kin Name *')" />
                    <x-text-input id="next_of_kin_name" class="block mt-1 w-full text-sm" type="text" name="next_of_kin_name" :value="old('next_of_kin_name')" required />
                    <x-input-error :messages="$errors->get('next_of_kin_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="next_of_kin_relationship" :value="__('Next of Kin Relationship *')" />
                    <x-text-input id="next_of_kin_relationship" class="block mt-1 w-full text-sm" type="text" name="next_of_kin_relationship" :value="old('next_of_kin_relationship')" placeholder="e.g. Parent, Spouse, Sibling" required />
                    <x-input-error :messages="$errors->get('next_of_kin_relationship')" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="next_of_kin_phone" :value="__('Next of Kin Phone Number *')" />
                    <x-text-input id="next_of_kin_phone" class="block mt-1 w-full text-sm" type="text" name="next_of_kin_phone" :value="old('next_of_kin_phone')" required />
                    <x-input-error :messages="$errors->get('next_of_kin_phone')" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- 3. SKILLS AND AREAS OF INTEREST -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-3">3. Skills & Areas of Interest</h3>
            <div class="space-y-4">
                <div>
                    <x-input-label for="occupation" :value="__('Occupation')" />
                    <x-text-input id="occupation" class="block mt-1 w-full text-sm" type="text" name="occupation" :value="old('occupation')" placeholder="e.g. Business Owner, Student, Teacher" />
                    <x-input-error :messages="$errors->get('occupation')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="talents_skills" :value="__('Talents & Special Skills')" />
                    <textarea id="talents_skills" name="talents_skills" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="List any skills (e.g. Accounting, IT, Event Planning, First Aid)...">{{ old('talents_skills') }}</textarea>
                    <x-input-error :messages="$errors->get('talents_skills')" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- ACCOUNT SECURITY -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-3">Account Security</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div x-data="{ show: false }">
                    <x-input-label for="password" :value="__('Password *')" />
                    <div class="relative mt-1">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            class="block w-full text-sm border-gray-300 rounded-md shadow-sm pr-10"
                            required
                            autocomplete="new-password"
                        >
                        <button
                            type="button"
                            @click="show = ! show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                        >
                            <svg x-show="! show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div x-data="{ show: false }">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password *')" />
                    <div class="relative mt-1">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="block w-full text-sm border-gray-300 rounded-md shadow-sm pr-10"
                            required
                            autocomplete="new-password"
                        >
                        <button
                            type="button"
                            @click="show = ! show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                        >
                            <svg x-show="! show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- 4. MEMBER DECLARATION & TERMS -->
        <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-200">
            <h3 class="text-sm font-bold text-emerald-900 uppercase tracking-wider mb-2">4. Member Declaration & Terms</h3>
            <p class="text-xs text-gray-600 mb-3">
                By registering, I hereby declare that the information provided above is accurate and true to the best of my knowledge. I agree to abide by the rules, policies, and constitution of this Community-Based Organization (CBO).
            </p>

            <label class="inline-flex items-center">
                <input type="checkbox" name="terms_accepted" value="1" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" required {{ old('terms_accepted') ? 'checked' : '' }}>
                <span class="ms-2 text-sm font-semibold text-gray-800">I accept the Member Declaration & Terms *</span>
            </label>
            <x-input-error :messages="$errors->get('terms_accepted')" class="mt-1" />
        </div>

        <!-- SUBMIT -->
        <div class="flex items-center justify-between pt-2">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" style="background-color: #047857; color: #ffffff; padding: 10px 24px; font-size: 14px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer;">
                {{ __('Submit Registration') }}
            </button>
        </div>
    </form>
</x-guest-layout>