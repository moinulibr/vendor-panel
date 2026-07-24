@extends('layouts.app')

@section('content')
@php
    // Group permissions by prefix
    $groupedPermissions = $permission->groupBy(function($perm) {
        return explode('.', $perm->name)[0];
    })->sortKeys();
@endphp

<div class="content py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="fw-bold mb-0">Update Roles</h4>
            </div>
        </div>

        <!-- Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Role Name -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold">Name</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Enter role name"
                                       value="{{ $role->name }}">
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold">Permissions</label>

                                <div class="mt-2">
                                    @foreach($groupedPermissions as $group => $perms)
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-2">
                                                {{ ucwords(str_replace('_',' ', $group)) }}
                                            </h6>

                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($perms as $value)
                                                    <input
                                                        type="checkbox"
                                                        class="btn-check"
                                                        name="permission[{{$value->id}}]"
                                                        id="perm{{$value->id}}"
                                                        value="{{$value->id}}"
                                                        autocomplete="off"
                                                        {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}
                                                    >
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

                        <!-- Submit -->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Update
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
