<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        
        @php
            $p_price=getProductDiscount($product);
            $discount_price=$p_price['discount_price'];
            $discount=$p_price['discount'];
        
        @endphp

        <!-- Header -->
        <div class="modal-header bg-light">
            <h5 class="modal-title fw-bold">
                Product Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <div class="container-fluid">

                <!-- BASIC INFO -->
                <div class="row g-3 mb-3">

                    <!-- Product Info -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header fw-bold">
                                Basic Information
                            </div>
                            <div class="card-body small">
                                <p><b>Name:</b> {{ $product->name }}</p>
                                <p><b>Name (Bangla):</b> {{ $product->name_bangla }}</p>
                                <p><b>SKU:</b> {{ $product->sku }}</p>
                                <p><b>Type:</b> {{ ucfirst($product->type) }}</p>
                                <p><b>Stock Manage:</b> {{ $product->stock_manage ? 'Yes' : 'No' }}</p>
                                @if($product->stock_manage)
                                <p><b>Current Stock :</b> {{ $product->stocks->sum('qty_available') }}</p>
                                @endif
                                <p><b>Stock Alert:</b> {{ $product->stock_alert }}</p>
                                <p><b>Ecommerce:</b> {{ $product->is_ecom == '1' ? 'Active':'De-active'}}</p>
                                <p><b>Featured:</b> {{ $product->is_feature == '1' ? 'Active':'De-active'}}</p>
                                <p><b>Trending:</b> {{ $product->is_reco == '1' ? 'Active':'De-active'}}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Category -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header fw-bold">
                                Pricing & Category
                            </div>
                            <div class="card-body small">
                                <p><b>Purchase Price:</b> {{ priceFormate($product->purchase_price) }}</p>
                                <p><b>MRP:</b> {{ priceFormate($product->sell_price) }}</p>
                                <p><b>Category:</b> {{ $product->category->name ?? 'N/A' }}</p>
                                <p><b>Sub Category:</b> {{ $product->subcategory->name ?? 'N/A' }}</p>
                                <p><b>Brand:</b> {{ $product->brand->name ?? 'N/A' }}</p>
                                <p><b>Unit:</b> {{ $product->unit->name ?? 'N/A' }}</p>
                                
                                @if($discount_price>0)
                                    <p> Discount Advailable : <b> {{ $discount->title }} </b> <br>
                                    <span class="text-red">{{ priceFormate($product->sell_price - $discount_price) }}</span>
                                    <del class="text-muted">{{ priceFormate($product->sell_price) }}</del>
                                    <span class="text-muted">({{ number_format($discount->amount,0) }} {{$discount->discount_type =='percentage'?'%':'tk'}} off)</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Image & Video -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header fw-bold">
                                Media - {{ $product->formatted_size }}
                            </div>
                            <div class="card-body text-center">
                                <img
                                    src="{{ getImage('products',$product->image) }}"
                                    class="img-fluid rounded mb-2"
                                    style="max-height:220px; object-fit:contain;"
                                >

                                @if($product->video_link)
                                    <div class="ratio ratio-16x9 mt-2">
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $product->video_link }}"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <!-- WARRANTY & RETURN -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-bold">Warranty</div>
                            <div class="card-body small">
                                <p><b>Available:</b> {{ $product->warranty_available ? 'Yes' : 'No' }}</p>
                                @if($product->warranty_days)
                                    <p><b>Days:</b> {{ $product->warranty_days }}</p>
                                @endif
                                <p>{{ $product->warranty_note }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-bold">Return Policy</div>
                            <div class="card-body small">
                                <p><b>Available:</b> {{ $product->return_available ? 'Yes' : 'No' }}</p>
                                @if($product->return_days)
                                    <p><b>Days:</b> {{ $product->return_days }}</p>
                                @endif
                                <p>{{ $product->return_note }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header fw-bold">Description</div>
                    <div class="card-body small">
                        {!! $product->description !!}
                        <hr>
                        {!! $product->specification !!}
                    </div>
                </div>

                <!-- GALLERY -->
               {{--@if($product->images)
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                            <span>Product Gallery</span>
                            <span class="badge bg-secondary">
                                {{ count($product->images) }} Images
                            </span>
                        </div>
                    
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($product->images as $img)
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="card h-100 position-relative shadow-sm border-0">
                    
                                        <!-- Delete Button -->
                                        <a href="{{ route('multiImageDelete', $img->id) }}"
                                           class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete"
                                           title="Delete">
                                            ✖
                                        </a>
                    
                                        <!-- Image -->
                                        <img
                                            src="{{ getImage('products',$img->image) }}"
                                            class="card-img-top rounded-top"
                                            style="height:140px; object-fit:cover;">
                    
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif --}}

                <!-- GALLERY -->
                @if($product->images && count($product->images) > 0)
                    <div class="card shadow-sm border-0 mb-4 rounded-3">
                        <!-- Card Header -->
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                            <h6 class="fw-bold m-0 text-dark">
                                <i class="bi bi-images me-1 text-primary"></i> Product Gallery
                            </h6>
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill">
                                {{ count($product->images) }} Images
                            </span>
                        </div>
                    
                        <!-- Card Body -->
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                @foreach($product->images as $img)
                                <!-- Grid: Mobile-এ 12/6, Tablet-এ 4, Desktop-এ 3/2 -->
                                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="card h-100 shadow-sm border rounded-3 overflow-hidden position-relative gallery-card">
                                        
                                        <!-- Delete Button (Top Right Absolute Badge) -->
                                        <a href="{{ route('multiImageDelete', $img->id) }}"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle p-0 d-flex align-items-center justify-content-center delete-btn"
                                        style="width: 28px; height: 28px; z-index: 10; opacity: 0.9;"
                                        title="Delete Image"
                                        onclick="return confirm('Are you sure you want to delete this image?');">
                                        ✖
                                        </a>

                                        <!-- Image Wrapper -->
                                        <div class="bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 150px;">
                                            <img src="{{ getImage('products', $img->image) }}"
                                                alt="Product Image"
                                                class="w-100 h-100 img-fluid"
                                                style="object-fit: cover; transition: transform 0.3s ease;">
                                        </div>
                                        @if($img->formatted_size)
                                        <!-- Card Footer / Image Info Section -->
                                        <div class="card-footer bg-white border-top-0 p-2 text-center pb-0">
                                            <!-- Image File Size (DB-তে size column থাকলে) -->
                                            <small class="text-muted d-block fw-medium" style="font-size: 13px;">
                                                <i class="bi bi-hdd-fill me-1"></i>
                                                {{ isset($img->formatted_size) ? $img->formatted_size : 'N/A' }} 
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif


                <!-- VARIATIONS -->
                @if($product->type == 'variable')
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Product Variations</div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Purchase</th>
                                    <th>Sell</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variations as $variation)
                                <tr>
                                    <td>{{ $variation->name }}</td>
                                    <td>{{ $variation->sub_sku }}</td>
                                    <td>{{ $variation->purchase_price }}</td>
                                    <td>{{ $variation->sell_price }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

    </div>
</div>
