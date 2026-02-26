@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
<style>
    body, h1, h2, h3, p, span {
        font-family: 'Poppins', Arial, sans-serif !important;
    }
</style>
@section('content')
<div class="center-viewport">
    <div class="container py-5" style="max-width: 900px;">
        <div class="settings-container">
            <h2 class="mb-4">Hydration Settings</h2>
            <div class="row justify-content-center">
                @foreach($settings as $setting)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="intensity-title mb-3 text-center">{{ ucfirst($setting->intensity) }}</div>
                                <ul class="list-unstyled flex-grow-1">
                                    <li class="mb-2"><strong>Hydration reminder every:</strong> {{ $setting->hydration_reminder }} minutes</li>
                                    <li class="mb-2"><strong>Break duration:</strong> {{ $setting->break_duration }} minutes</li>
                                    <li class="mb-2"><strong>Break reminder every:</strong> {{ $setting->break_reminder }} minutes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('hydration.edit') }}" class="btn dark-btn px-4 py-2 rounded-pill">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection