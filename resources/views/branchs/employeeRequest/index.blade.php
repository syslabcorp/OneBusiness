@extends('layouts.custom')

@section('content')
<head>
<style>
table { 
  width: 100%; 
  border-collapse: collapse; 
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}
</style>
</head>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <table>
    <thead>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Job Title</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>James</td>
        <td>Matman</td>
        <td>Chief Sandwich Eater</td>
    </tr>
    </tbody>
</table>
        </div>
      </div>
</section>
@endsection
