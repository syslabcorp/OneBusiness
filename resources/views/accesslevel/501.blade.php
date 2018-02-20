@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">   
      <!-- Page content -->
		<div class="col-md-12">
			<center>
				<div class="error-page">
					<h2 class="headline text-info"> 501</h2>
					<div class="error-content">
						<h3><i class="fa fa-warning text-yellow"></i> Oops! Something Went Wrong.</h3>
						<p>
							Please setup a connection,database and access rights for this corporation and try again.
						</p>
					</div>
				</div>
			</center>
		</div>
	</div>
</div>
@endsection