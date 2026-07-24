@extends('layouts.app')

@section('content')

<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Roles</h4>
                <h6>Manage your Roles</h6>
            </div>
        </div>
        
        @can('roles.create')
        <div class="page-btn">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i>Add Roles
            </a>
        </div>
        @endcan
    </div>

    <!-- Roles List -->
    <div class="card">
        <div class="card-body p-0" id="data">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="100px">No</th>
                        <th>Name</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @can('roles.edit')
                                <a class="btn btn-primary btn-sm"
                                   href="{{ route('roles.edit', $role->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                            @endcan

                            @can('roles.delete')
                                @if(empty($role->not_delete))
                                    <!-- Normal Delete -->
                                    <form method="POST"
                                          action="{{ route('roles.destroy', $role->id) }}"
                                          style="display:inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this role?')">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $roles->links('pagination::bootstrap-5') !!}
        </div>
    </div>
    <!-- /Roles List -->
</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
