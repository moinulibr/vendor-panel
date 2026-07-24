@foreach($contact_address as $i=>$address)
<div class="col-12 col-md-6 col-lg-4">
    <div class="option-box d-flex align-items-start gap-3 cursor-pointer">
        <input class="form-check-input mt-1" style="cursor: pointer;"
               type="radio"
               name="shipping_id"
               id="addr{{ $address->id }}"
               value="{{ $address->id }}"
               {{$i==0 ?'checked':''}}>
    
        <label class="form-check-label w-100" for="addr{{ $address->id }}">
            <strong>{{ $address->name }}</strong><br>
            {{ $address->address }}<br>
            
            @if($address->district)
                {{ $address->district->name }} - {{ $address->upazila->name??''}}<br>
            @endif
            📞 {{ $address->phone }}
        </label>
    </div>
</div>
@endforeach
