<div class="modal-dialog modal-lg">
  <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
            <h5 class="modal-title fw-semibold">Vendor Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <div class="row g-3">

                <!-- First & Last Name -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">First Name</small>
                        <span>{{ $vendor->name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Last Name</small>
                        <span>{{ $vendor->last_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Email & Mobile -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Email</small>
                        <span>{{ $vendor->email ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Mobile</small>
                        <span>{{ $vendor->mobile ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Shop Name & Trade License -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Shop Name</small>
                        <span>{{ $vendor->vendorAddress->shop_name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Trade License</small>
                        <span>{{ $vendor->vendorAddress->trade_license ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Fax & Website -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">FAX</small>
                        <span>{{ $vendor->vendorAddress->fax ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Website</small>
                        <span>{{ $vendor->vendorAddress->website ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Gender & Status -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Gender</small>
                        <span>{{ ucfirst($vendor->gender) ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Status</small>
                        <span>{{ $vendor->status == 1 ? 'Active' : 'De-Active' }}</span>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Date of Birth</small>
                        <span>{{ $vendor->dob ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Roles -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Roles</small>
                        <span>{{ $vendor->roles->pluck('name')->join(', ') ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Business Address -->
                <div class="col-12">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Business Address</small>
                        <span>{{ $vendor->vendorAddress->address ?? 'N/A' }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>
        </div>

  </div>
</div>
