<table class="table table-bordered">
   <tr>
       <th>No</th>
       <th width="280px">Action</th>
       <th>Name</th>
       <th>Email</th>
       <th>Roles</th>
       <th>Mobile</th>
       <th>Gender</th>
       <th>Date Of Birth</th>
       <th>Status</th>
   </tr>
   @foreach ($data as $key => $user)
    <tr>
        <td>{{ $key+1 }}</td>
        <td class="action-table-data">
            <div class="dropdown action-dropdown-wrap">
                <button class="btn btn-sm btn-icon"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
        
                <ul class="dropdown-menu dropdown-menu-end action-dropdown">
                    <li>
                        <a class="dropdown-item btn_modal" href="{{ route('vendors.show',[$user->id])}}">
                            <i class="fa fa-eye"></i>
                            <span>View</span>
                        </a>
                    </li>
                    
                    @can('vendors.update')
                    <li>
                        <a class="dropdown-item btn_modal" href="{{ route('vendors.edit',[$user->id])}}">
                            <i class="fa fa-edit"></i>
                            <span>Edit</span>
                        </a>
                    </li>
                    @endcan
                    
                   @can('vendors.delete')
                        <li>
                            <a  href="{{ route('vendors.destroy',[$user->id])}}" class="dropdown-item text-danger delete">
                                <i class="fa fa-trash"></i>
                                <span>Delete</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </td>
        <td>{{ $user->name }} {{ $user->last_name }}</td>
        <td>{{ $user->email }}</td>
        
        <td>
          @if(!empty($user->getRoleNames()))
            @foreach($user->getRoleNames() as $v)
               <label class="badge bg-success">{{ $v }}</label>
            @endforeach
          @endif
        </td>
        
        <td>{{ $user->mobile }}</td>
        <td>{{ ucfirst($user->gender) }}</td>
        <td>{{ $user->dob }}</td>
        
        <td>
            @if($user->status)
		    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
		    @else
		    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
		    @endif
        </td>
    </tr>
 @endforeach
</table>
<p> {{$data->render()}} </p>