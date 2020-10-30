<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Researchers') }}
        </h2>
    </x-slot>
    {{--Researcher--}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-form-section submit="">
                <x-slot name="title">
                    {{ __('Researcher') }}
                </x-slot>
                <x-slot name="description">
                    Add information about the researcher here
                </x-slot>
                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-6 min-h-40">
                        <livewire:forms.create-researcher/>
                    </div>

                </x-slot>
            </x-jet-form-section>
        </div>
    </div>
</x-app-layout>
