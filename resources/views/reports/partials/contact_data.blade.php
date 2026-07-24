<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				
				<th>Total {{ $type=='customer' ?'Sell':'Purchase'}}</th>
				<th>Total {{ $type=='customer' ?'Sell':'Purchase'}} Paid </th>
				<th>Total {{ $type=='customer' ?'Sell':'Purchase'}} Due</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody>
		    @foreach($items as $i=>$item)
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<a href="javascript:void(0);" class="avatar avatar-md bg-light-900 p-1 me-2">
							<img class="object-fit-contain" src="{{ getImage('contacts',$item->image)}}" alt="img">
						</a>
						<a href="javascript:void(0);">{{ $item->name}}</a>
					</div>
				</td>
				<td>{{ $item->email}}</td>
				<td>{{ $item->mobile}}</td>
				
				<td>{{ $item->total_sell}}</td>
				<td>{{ $item->total_sell_paid}}</td>
				<td>{{ $item->total_due}}</td>
				<td>{{ $item->address}}</td>
			</tr>
		    @endforeach
		</tbody>
		<tfoot>
			<td class="bg-light fw-bold p-3 fs-16" colspan="3">Total</td>
			<td class="bg-light fw-bold p-3 fs-16">{{ $items->sum('total_sell')}}</td>
			<td class="bg-light fw-bold p-3 fs-16">{{ $items->sum('total_sell_paid')}}</td>
			<td class="bg-light fw-bold p-3 fs-16">{{ $items->sum('total_due')}}</td>
			<td class="bg-light"></td>
			<td class="bg-light"></td>
		</tfoot>
	</table>
</div>