@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Session History</h1>
    @if($sessions->isEmpty())
        <div class="alert alert-info">No sessions found.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sport</th>
                    <th>Beginner Duration</th>
                    <th>Intermediate Duration</th>
                    <th>Advanced Duration</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                    <tr>
                        <td>{{ $session->sport }}</td>
                        <td>{{ $session->beginner_duration ? sprintf('%d hr %d min', intdiv($session->beginner_duration, 60), $session->beginner_duration % 60) : 'Not Set' }}</td>
                        <td>{{ $session->intermediate_duration ? sprintf('%d hr %d min', intdiv($session->intermediate_duration, 60), $session->intermediate_duration % 60) : 'Not Set' }}</td>
                        <td>{{ $session->advanced_duration ? sprintf('%d hr %d min', intdiv($session->advanced_duration, 60), $session->advanced_duration % 60) : 'Not Set' }}</td>
                        <td>{{ $session->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
