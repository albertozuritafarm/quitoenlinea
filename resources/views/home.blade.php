@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-3 center">
            <!--<h1>Bienvenido a su sistema HIT</h1>-->
            

            <!--<div class="card-body">-->


            <!--</div>-->
        </div>
        <br>
        <div class="col-md-5 col-md-offset-3">
            @if (session('ValidateUserRoute'))
            <div class="alert alert-danger">
                <center><strong>
                    {{ session('ValidateUserRoute') }}

                    </strong></center>
            </div>
            @endif
            
        </div>
    </div>
</div>
@endsection
