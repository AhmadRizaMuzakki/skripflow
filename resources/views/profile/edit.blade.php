<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Profil</h1>
            <p class="text-sm text-slate-500">Kelola informasi akun Anda.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-soft sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-soft sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-soft sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
    </div>
</x-app-layout>
