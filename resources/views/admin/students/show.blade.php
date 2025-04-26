@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('admin.student_details') }}</h3>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">
                         {{ __('admin.back') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Summary list -->
                        <div class="col-md-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>{{ __('admin.name') }}:</strong> {{ $student->name }}</li>
                                <li class="list-group-item"><strong>{{ __('admin.phone') }}:</strong> {{ $student->phone ?? __('admin.not_available') }}</li>
                                <li class="list-group-item"><strong>{{ __('admin.classes') }}:</strong> {{ $student->classes->pluck('name')->join(', ') ?: __('admin.no_classes_found') }}</li>
                                <li class="list-group-item"><strong>{{ __('admin.muhafez') }}:</strong> {{ $student->muhafez->name ?? __('admin.not_available') }}</li>
                            </ul>
                        </div>
                        <!-- Performance chart -->
                        <div class="col-md-8">
                            <h5 class="card-subtitle mb-3">{{ __('admin.student_performance') }}</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="performanceChart" width="700" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [
                {
                    label: '{{ __('admin.quraan') }}',
                    data: {!! json_encode($quraanDegrees) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                },
                {
                    label: '{{ __('admin.werd') }}',
                    data: {!! json_encode($werdDegrees) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.6)'
                },
                {
                    label: '{{ __('admin.attendance') }}',
                    data: {!! json_encode($attendanceDegrees) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }
            ]
        },
        options: {
            scales: {
                x: { stacked: false },
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
