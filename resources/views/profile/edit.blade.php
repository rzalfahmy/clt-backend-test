<x-app-layout>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk'] text-2xl font-bold text-white">
            Profile Settings
        </h2>
    </x-slot>

    <div class="shell space-y-8">

        <!-- PROFILE INFO -->
        <div class="panel p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="panel p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- DELETE -->
        <div class="panel p-6 sm:p-8 border border-red-400/20">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>

</x-app-layout>
