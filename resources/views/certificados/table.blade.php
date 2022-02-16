<div class="table-responsive">
    <table class="table" id="certificados-table">
        <thead>
            <tr>
                <th>User Id</th>
        <th>Version</th>
        <th>Autor</th>
        <th>Titulo</th>
        <th>Contenido</th>
        <th>Uuid</th>
        <th>Red</th>
        <th>Traza</th>
        <th>Ipfs</th>
        <th>Clave</th>
        <th>Bloqueado</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($certificados as $certificado)
            <tr>
                       <td>{{ $certificado->user_id }}</td>
            <td>{{ $certificado->version }}</td>
            <td>{{ $certificado->autor }}</td>
            <td>{{ $certificado->titulo }}</td>
            <td>{{ $certificado->contenido }}</td>
            <td>{{ $certificado->uuid }}</td>
            <td>{{ $certificado->red }}</td>
            <td>{{ $certificado->traza }}</td>
            <td>{{ $certificado->ipfs }}</td>
            <td>{{ $certificado->clave }}</td>
            <td>{{ $certificado->bloqueado }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['certificados.destroy', $certificado->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('certificados.show', [$certificado->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('certificados.edit', [$certificado->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("Are you sure want to delete this record ?")']) !!}
                           </div>
                           {!! Form::close() !!}
                       </td>
                   </tr>
        @endforeach
        </tbody>
    </table>
</div>
