@extends('layouts.custom')

@section('content')
<script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>


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

        <div class="table-responsive">
          <table class="table" style="border: 1px solid #ddd;">
            <tbody>
              <tr class="done">
                <td>Tempxyz_PO_0123154</td>
                <td>
                  Document Created 
                  <a href="#">View</a>
                  <span class=" pull-right far fa-check-circle"></span>
                </td>
              </tr>
              <tr class="done" >
                <td>Tempxza_PO_12312</td>
                <td>
                  No items to be ordered
                  <span class=" pull-right far fa-check-circle"></span>
                </td>
              </tr>
              <tr>
                <td>Tempxza_PO_123120 </td>
                <td>
                  Caculating order quantities(20%)
                  <span class=" pull-right fa fa-circle-notch fa-pulse"></span>
                </td>
              </tr>
              <tr>
                <td>Tempxza_PO_1231201 </td>
                <td>
                  Pending
                  <span class=" pull-right fas fa-circle-notch fa-pulse"></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="loader"></div>

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