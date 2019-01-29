@extends('layouts.plane')

@section('body')
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <top-navigation></top-navigation>


        <side-navigation token="{{ csrf_token() }}"></side-navigation>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        
        <router-view></router-view>
        
        
    </div>
</div>
@stop

