@php
$data = $this->getData();
@endphp
<x-filament::page>
    <x-filament::card>
        <div class="title">
            <h1> Nama : {{ $data['name'] }}</h1>
        </div>
        <div class="content">
            <ul>
                <li>Email : {{ $data['email'] }}</li>
                <li>Roles : {{ $data['roles'][0]['name'] }}</li>
            </ul>
        </div>
    </x-filament::card>
</x-filament::page>