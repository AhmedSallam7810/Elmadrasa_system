@extends('layouts.admin')

@section('title', 'Report Results')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Report Results</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Students</span>
                                    <span class="info-box-number">{{ $students->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Date Range</span>
                                    <span class="info-box-number">{{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chalkboard"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Class</span>
                                    <span class="info-box-number">{{ $class->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Attendance Rate</th>
                                    <th>Average Score</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->phone }}</td>
                                        <td>{{ number_format($student->attendance_rate, 2) }}%</td>
                                        <td>{{ number_format($student->average_score, 2) }}</td>
                                        <td>
                                            @if($student->attendance_rate >= 80 && $student->average_score >= 60)
                                                <span class="badge badge-success">Good Standing</span>
                                            @elseif($student->attendance_rate < 80 || $student->average_score < 60)
                                                <span class="badge badge-warning">Needs Attention</span>
                                            @else
                                                <span class="badge badge-danger">At Risk</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No students found in this class for the selected date range.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($students->isNotEmpty())
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Class Performance Summary</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="performanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('performanceChart').getContext('2d');
    var performanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($students->pluck('full_name')) !!},
            datasets: [{
                label: 'Attendance Rate',
                data: {!! json_encode($students->pluck('attendance_rate')) !!},
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                borderWidth: 1
            }, {
                label: 'Average Score',
                data: {!! json_encode($students->pluck('average_score')) !!},
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush
