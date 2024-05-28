@extends('layouts.main')

@section('content')
    @livewire('category-items', ['id' => $id])
@endsection
