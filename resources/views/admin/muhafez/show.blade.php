@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.muhafez_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.muhafezs.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px">{{ __('admin.name') }}</th>
                                    <td>{{ $muhafez->name }}</td>
                                </tr>
                                {{-- <tr>
                                    <th>{{ __('admin.email') }}</th>
                                    <td>{{ $muhafez->email }}</td>
                                </tr> --}}
                                <tr>
                                    <th>{{ __('admin.phone') }}</th>
                                    <td>{{ $muhafez->phone }}</td>
                                </tr>
                                {{-- <tr>
                                    <th>{{ __('admin.address') }}</th>
                                    <td>{{ $muhafez->address }}</td>
                                </tr> --}}
                                <tr>
                                    <th>{{ __('admin.status') }}</th>
                                    <td>
                                        <span class="badge {{ $muhafez->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $muhafez->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('admin.created_at') }}</th>
                                    <td>{{ $muhafez->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('admin.updated_at') }}</th>
                                    <td>{{ $muhafez->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.muhafezs.edit', $muhafez->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                        </a>
                        <form action="{{ route('admin.muhafezs.destroy', $muhafez->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
