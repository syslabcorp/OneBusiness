@extends('layouts.custom')

@section('content')

<!-- Page content -->
<section class="content">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-9">
            <h4>Retail Order Template</h4>
            
          </div>
          <div class="col-xs-3">

          </div>
        </div>
      </div>
      <div class="panel-body" style="margin: 30px 0px;">
        <div class="row purchase_menu" style="margin-bottom: 20px;">
            <ul class="purchase_order_style navbar-nav" >
                <li class="">
                    <a href="{{route('purchase_order.create_manual') }}">Manual P.O.</a>
                </li>

                <li class="active">
                    <a>Auto-generate P.O.</a>
                </li>
                <li class="last_item"></li>
            </ul>
        </div>

        <div class="row purchase_header_form">
          <form class="form-inline" action="/action_page.php">
            <div class="form-group">
              <label>City</label>
              <select class="form-control" style="width: 300px;" name="" id="">
                <option value=""></option>
                <option value=""></option>
              </select>
            </div>
            <div class="checkbox">
              <label><input type="checkbox"> All Cities</label>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Template Code</th>
                <th>Ave Cycle</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>DFGDFG</td>
                <td>30</td>
              </tr>
              <tr>
                <td>DVO-NX-temp1</td>
                <td>30</td>
              </tr>
              <tr>
                <td>nx-dvo-adm-pr</td>
                <td>30</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="pull-right">
          <button class="btn btn-primary">Create P.O.</button>
        </div>

        </div>

      </div>
    
    </div>
  </div>
</div>

</section>

@endsection