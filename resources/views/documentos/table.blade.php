<div class="table-responsive">
    <table class="table" id="documentos-table">
        <thead>
            <tr>
                <th>User Id</th>
        <th>Version</th>
        <th>Titulo</th>
        <th>Contenido</th>
        <th>Uuid</th>
        <th>Protegido</th>
        <th>Bloqueado</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($documentos as $documento)
            <tr>
                       <td>{{ $documento->user_id }}</td>
            <td>{{ $documento->version }}</td>
            <td>{{ $documento->titulo }}</td>
            <td>{{ $documento->contenido }}</td>
            <td>{{ $documento->uuid }}</td>
            <td>{{ $documento->protegido }}</td>
            <td>{{ $documento->bloqueado }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['documentos.destroy', $documento->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('documentos.show', [$documento->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('documentos.edit', [$documento->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("Are you sure want to delete this record ?")']) !!}
                           </div>
                           {!! Form::close() !!}
                       </td>
                   </tr>
        @endforeach
        </tbody>
    </table>
</div>
