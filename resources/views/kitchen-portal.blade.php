@extends('layouts.kitchen')

@section('content')
    <div class="bg-gray-900 p-4">
        <livewire:kitchen-nav />
    </div>

    <div class="container mx-auto p-6">
        <livewire:kitchen-orders />
    </div>
@endsection
