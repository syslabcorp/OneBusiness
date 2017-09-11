@extends('layouts.app')

@section('content')

<h3 class="text-center">Manage Locations</h3>
<div class="container-fluid">
    <div class="row">
	     <div class="col-md-2">
    <!--menu here-->
        </div>
        <div class="col-md-8">
		    <div class="panel panel-default">
		    	<div class="panel-heading"> List of Provinces 

                @if(\Auth::user()->checkAccess("Locations", "A"))
                    <a href="{{ URL('add_province') }}" class="pull-right">Add Province</a>
                @endif
                </div>
		    	<div class="panel-body">
		    			 <table id="list_modul" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SNo.</th>
                                <th>Province</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
	                        <!--tr>
	                        	<td> cities here</td>
	                 		</tr-->
	                 		   @foreach($provs as $key=>$det)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $det->Province}}</td>
                                    <td>
                                    <a href="{{ URL::to('view_cities/'.$det->Prov_ID) }}" class="btn btn-success btn-md blue-tooltip" data-title="View" data-toggle="tooltip" data-placement="top" title="View Province"><span class="glyphicon glyphicon-eye-open"></span></a>

                                    <a href="{{ URL('add_province/'.$det->Prov_ID) }}" class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccess('Locations', 'E') ? '' : 'disabled' }}"  data-title="Edit" data-toggle="tooltip" data-placement="top" title="Edit Province"><span class="glyphicon glyphicon-pencil"></span></a>
                                   </td>

                                </tr>  
                           		@endforeach
                        </tbody>
                    </table>
		    	</div>
		    </div>
	    </div>
    </div>
</div>

@endsection

