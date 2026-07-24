<table class="table table-bordered">
   <tr>
       <th>No</th>
       <th>Name</th>
       <th>Email</th>
       <th>Roles</th>
       <th>Status</th>
       <th width="280px">Action</th>
   </tr>
   @foreach ($data as $key => $user)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
          @if(!empty($user->getRoleNames()))
            @foreach($user->getRoleNames() as $v)
               <label class="badge bg-success">{{ $v }}</label>
            @endforeach
          @endif
        </td>
        
        <td>
            @if($user->status)
		    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
		    @else
		    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
		    @endif
        </td>

        <td class="action-table-data">
            <div class="edit-delete-action">
                @can('users.edit')
                <a class="me-2 p-2 btn_modal" href="{{ route('users.edit',[$user->id])}}">
                    <i class="fa fa-edit"></i>
                </a>
                @endcan
                @can('users.delete')
                <a  href="{{ route('users.destroy',[$user->id])}}" class="delete">
                    <i class="fa fa-trash"></i>
                </a>
                @endcan
            </div>
            
        </td>
    </tr>
 @endforeach
</table>
<p> {{$data->render()}} </p>