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
            <div class="top-bar mb-3">
                <a href="{{ url()->previous() }}" class="back-arrow me-2" title="Back">&#8592;</a>
                <h2 class="mb-4 d-inline-block">Edit Hydration Settings</h2>
            </div>
            <form action="{{ route('hydration.update') }}" method="POST">
                @csrf
                <div class="card p-4 rounded-4">
                    @foreach($settings as $setting)
                        <div class="intensity-title mb-3 text-center">{{ ucfirst($setting->intensity ?? $setting->level) }}</div>
                        <div class="mb-2">
                            <label>Hydration reminder every</label>
                            <input type="number" name="settings[{{ $setting->id }}][hydration_reminder]"
                                   value="{{ $setting->hydration_reminder }}"
                                   class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Break duration</label>
                            <input type="number" name="settings[{{ $setting->id }}][break_duration]"
                                   value="{{ $setting->break_duration }}"
                                   class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Break reminder every</label>
                            <input type="number" name="settings[{{ $setting->id }}][break_reminder]"
                                   value="{{ $setting->break_reminder }}"
                                   class="form-control">
                        </div>
                        <hr>
                    @endforeach
                    <button type="submit" class="btn dark-btn">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection