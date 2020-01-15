@if (Session::has('save'))
    @section('implemantations')
        <script>
            $( function() {
                $.alert('<strong>Mensaje del Sistema</strong> <br />Los Datos se han CREADO exitosamente',{
                    closeTime: 2500,
                    type: 'info',// danger, success, warning or info
                    position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>
    @endsection
@endif

@if (Session::has('delete'))
    @section('implemantations')
        <script>
            $( function() {
                $.alert('<strong>Mensaje del Sistema</strong> <br />Los Datos se han ELIMINADO exitosamente',{
                    closeTime: 2500,
                    type: 'warning',// danger, success, warning or info
                    position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>
    @endsection
@endif

@if (Session::has('update'))
    @section('implemantations')
        <script>
            $( function() {
                $.alert('<strong>Mensaje del Sistema</strong> <br />Los Datos se han ACTUALIZADO exitosamente',{
                    closeTime: 3500,
                    type: 'info',// danger, success, warning or info
                    position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>
    @endsection
@endif

@if (Session::has('error'))
    @section('implemantations')
        <script>
            $( function() {
                $.alert('<strong>Mensaje del Sistema</strong> <br />UPS, ha ocurrido un error...',{
                    closeTime: 3500,
                    type: 'danger',// danger, success, warning or info
                    position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>
    @endsection
@endif

@if (Session::has('message'))
    @section('implemantations')
        <script>
            $( function() {
                $.alert('<strong>Mensaje del Sistema</strong> <br />UPS, ha ocurrido un error...',{
                    closeTime: 3500,
                    type: 'info',// danger, success, warning or info
                    position: ['top-right', [0, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>
    @endsection
@endif

@if (Session::has('alert'))
    @section('implemantations')
        <script>
            $( function() {
                $.alert('<strong>Mensaje del Sistema</strong> <br />UPS, ha ocurrido un error...',{
                    closeTime: 3500,
                    type: 'warning',// danger, success, warning or info
                    position: ['top-right', [0, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>
    @endsection
@endif

@if (Session::has('alert-uniope'))

        <script>
            $( function() {
                $.alert('<strong><h3>Acceso Denegado!</h3></strong> <br />Ud. no posee Unidades Operativas <strong>ACTIVAS</strong><br /> comuniquese con el administrador del sistema',{
                    closeTime: 5500,
                    type: 'warning',// danger, success, warning or info
                    position: ['top-right', [0, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>

@endif
@if (Session::has('alert-tipact'))

        <script>
            $( function() {
                $.alert('<strong><h3>Acceso Denegado!</h3></strong> <br />Ud. no posee Tipo de Actividades <strong>ACTIVAS</strong><br /> comuniquese con el administrador del sistema',{
                    closeTime: 5500,
                    type: 'warning',// danger, success, warning or info
                    position: ['top-right', [0, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            });

        </script>

@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
