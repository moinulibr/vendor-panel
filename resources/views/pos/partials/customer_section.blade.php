@foreach($items as $item)
<div class="d-flex justify-content-between align-items-center p-3 mb-3 bg-white rounded shadow-sm"
     style="cursor:pointer"
     onclick="customerEntryNew({{ $item }})">

    <!-- Left side -->
    <div class="d-flex align-items-center gap-3">
        
        <!-- Add Button (click only this) -->
        <div
            class="btn btn-success btn-md d-flex align-items-center justify-content-center"
            onclick="event.stopPropagation(); customerEntry({{ $item }})">
            <i class="bi bi-plus-lg me-1"></i> Add
        </div>

        <!-- Customer Info -->
        <div>
            <h5 class="mb-1">{{ $item->name }}</h5>
            <p class="mb-0 text-muted" style="font-size:0.9rem;">
                Phone: {{ $item->mobile }}
            </p>
        </div>
    </div>

    <!-- Right side -->
    <div class="text-end">
        <p class="mb-1" style="font-size:0.85rem;">
            ID: {{ $item->contact_id }}
        </p>
        <p class="mb-0 fw-semibold" style="font-size:0.9rem;">
            Due: Tk {{ $item->total_sell - $item->total_sell_paid }}
        </p>
    </div>
</div>
@endforeach
