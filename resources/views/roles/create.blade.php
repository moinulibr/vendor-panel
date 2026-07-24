@extends('layouts.app')

@section('content')
@php
    // Group permissions by prefix
    $groupedPermissions = $permission->groupBy(function($perm) {
        return explode('.', $perm->name)[0]; // roles, products, users, etc.
    })->sortKeys();
@endphp

<div class="content py-4">
    <div class="container-fluid">

        <div class="page-header mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="fw-bold mb-0">Create Roles</h4>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="row g-3">

                        <!-- Role Name -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold">Name:</label>
                                <input type="text" name="name" placeholder="Enter role name" class="form-control">
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold">Permissions:</label>
                                <div class="mt-2">

                                    @foreach($groupedPermissions as $group => $perms)
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-capitalize mb-2">{{ str_replace('_', ' ', $group) }}</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($perms as $value)
                                                    <input type="checkbox" class="btn-check" name="permission[{{$value->id}}]" id="perm{{$value->id}}" autocomplete="off" value="{{$value->id}}">
                                                    <label class="btn btn-outline-primary btn-sm" for="perm{{$value->id}}">
                                                        {{ $value->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Submit
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
