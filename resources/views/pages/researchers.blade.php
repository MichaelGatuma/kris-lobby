<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-form-section submit="">
                <x-slot name="title">
                    {{ __('Add Researcher') }}
                </x-slot>
                <x-slot name="description">
                    Add Researcher Details here
                </x-slot>
                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-4 h-40">
                        <x-jet-label for="name" value="{{ __('Pick a User') }}"/>
                        <x-dropdown/>
                        <p>Gender</p>
                        <p>DOB</p>
                        <p>Phone number</p>
                        <p>Research Area</p>
                        <p>Department</p>
                        <p>Institution</p>
                        <p>affiliation</p>
                        <p>About researcer</p>
                        <p>Approved?</p>
                        <p>CV</p>
                        {{--                            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="createApiTokenForm.name" autofocus />--}}
                        <x-jet-input-error for="name" class="mt-2"/>
                    </div>
                </x-slot>
            </x-jet-form-section>
        </div>
    </div>
</x-app-layout>
