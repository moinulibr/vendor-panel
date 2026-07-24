<div class="modal-dialog modal-lg">
    <div class="modal-content">

        <!-- Header -->
        <div class="modal-header bg-light border-bottom">
            <h5 class="modal-title fw-semibold text-dark">
                Site Visit Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <!-- Body -->
<div class="modal-body p-3 p-md-4 bg-light">

    <div class="row g-4">

        <!-- Project Info -->
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header" style="background-color: #f8f9fa; color: #495057; font-weight: 600;border: 1px solid #eaeaea;">
                    Project Information
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-muted">Project:</span>
                        <span class="fw-medium">{{ $site_visit->project_name }}</span>
                    </p>

                    <p class="mb-2">
                        <span class="text-muted">Reference:</span>
                        <span>{{ $site_visit->ref_no }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Status:</span>
                        @if($site_visit->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">De-Active</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header" style="background-color: #f8f9fa; color: #495057; font-weight: 600;border: 1px solid #eaeaea;">
                    Contact Information
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-muted">Person:</span>
                        <span>{{ $site_visit->contact_person }}</span>
                    </p>

                    <p class="mb-2">
                        <span class="text-muted">Mobile:</span>
                        <span>{{ $site_visit->mobile }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Address:</span>
                        <span>{{ $site_visit->address }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Visit Info -->
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header" style="background-color: #f8f9fa; color: #495057; font-weight: 600;border: 1px solid #eaeaea;">
                    Visit Schedule
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-muted">Visiting Date:</span>
                        <span>{{ $site_visit->visiting_date }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Next Visit:</span>
                        <span>{{ $site_visit->next_visiting_date }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header" style="background-color: #f8f9fa; color: #495057; font-weight: 600;border: 1px solid #eaeaea;">
                    Notes & Description
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-muted">Note:</span>
                        <span>{{ $site_visit->note }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Description:</span>
                        {{ $site_visit->description }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>


        <!-- Footer -->
        <div class="modal-footer bg-light border-top">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    </div>
</div>
