<div class="table-responsive">
    <table class="table" id="teamInvitations-table">
        <thead>
            <tr>
                
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($teamInvitations as $teamInvitation)
            <tr>
                       
                       <td class=" text-center">
                           {!! Form::open(['route' => ['teamInvitations.destroy', $teamInvitation->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('teamInvitations.show', [$teamInvitation->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('teamInvitations.edit', [$teamInvitation->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("Are you sure want to delete this record ?")']) !!}
                           </div>
                           {!! Form::close() !!}
                       </td>
                   </tr>
        @endforeach
        </tbody>
    </table>
</div>
