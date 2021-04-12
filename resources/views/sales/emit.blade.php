@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="{{ assets('css/sales/productSelect.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{ assets('css/sales/index.css')}}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://kit.fontawesome.com/fd8222181b.js" crossorigin="anonymous"></script>

<div id="emitStep" class="container-fluid">
@include('sales.emitForm')
</div>
@endsection
