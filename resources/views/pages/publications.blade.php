<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Publications') }}
        </h2>
    </x-slot>
    {{--    Publication--}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-form-section submit="">
                <x-slot name="title">
                    {{ __('Publications') }}
                </x-slot>
                <x-slot name="description">
                    Add information about the researcher's publication
                </x-slot>
                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-6 min-h-40">
                        <livewire:forms.create-publication/>
                    </div>
                </x-slot>
            </x-jet-form-section>
        </div>
    </div>
</x-app-layout>
