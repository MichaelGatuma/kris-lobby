<div class="parent grid">
    <header class="bg-white border-b-2 p-8"><h1>{{ $title ?? 'No Title' }}</h1></header>
    <div class="left-sidebar p-8 bg-indigo-800 text-white border-r-2">Left Sidebar</div>
    <main class="p-8 bg-gray-100 w-full">
        {{ $slot }}
    </main>
    <div class="right-sidebar p-8 bg-indigo-800 text-white border-l-2">Right Sidebar</div>
    <footer class="bg-white p-8 text-center border-t-2">Footer</footer>
    <x-tall-notification />
</div>
@push('styles')
    <style>
        .parent { height: 100vh; grid-template: auto 1fr auto / auto 1fr auto }
        header { grid-column: 1 / 4; }
        .left-sidebar { grid-column: 1 / 2; }
        main { grid-column: 2 / 3; }
        .right-sidebar { grid-column: 3 / 4; }
        footer { grid-column: 1 / 4; }
    </style>
@endpush
