@extends('layouts.app')

@section('content')

<h3 class="text-center">System Settings</h3>
<div class="container-fluid">
    <div class="row">
	    <div class="col-md-2">
	<!--menu here-->
	    </div>
	    <div class="col-md-8">
		    <div class="panel panel-default">
		    	<div class="panel-heading"> Masterfiles

               <!-- @if(\Auth::user()->checkAccess("Locations", "A"))
                    <a href="{{ URL('add_province') }}" class="pull-right">Add Province</a>
                @endif-->
                </div>
		    	<div class="panel-body">
		    		<ul class="list-group">
						<li class="list-group-item"> Locations<span class="pull-right"></span></li>
						
						<li class="list-group-item"> Retail Items</li>
						
						<li class="list-group-item"> Services</li>
					</ul>
		    	</div>
		    </div>
	    </div>
    </div>
</div>

@endsection

