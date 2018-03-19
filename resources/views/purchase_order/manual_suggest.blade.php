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
      <div class="panel-body manual" style="margin: 30px 0px;">
        <div class="row purchase_menu" style="margin-bottom: 20px;">
            <ul class="purchase_order_style navbar-nav" >
                <li class="active">
                    <a>Manual P.O.</a>
                </li>

                <li class="">
                    <a href="{{route('purchase_order.create_automate') }}">Auto-generate P.O.</a>
                </li>
                <li class="last_item"></li>
            </ul>
        </div>

        <div class="row border_bottom">
          <span style="font-size: 36px">Generate P.O.:</span>
          <span>Total Pieces: 96</span>
          <span>Total Amount: 2,466.00</span>
        </div>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Item Code</th>
              @for($x = 1; $x <= $num_branch; $x++)
                <th class="text-center" colspan="5">Branch 1</th>
              @endfor
              <th class="blue_box">TOTAL</th>
              <th class="red_box" >BAL</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              @for($x = 1; $x <= $num_branch; $x++)
                <td>Ave.Sold Qty(Daily)</td>
                <td>Mult</td>
                <td>Stock(w/in-Trans)</td>
                <td>Pending(for PO)</td>
                <td class="blue_box">Qty</td>
              @endfor
              <td class="red_box"></td>
              <td class="blue_box"></td>
              <td></td>
            </tr>
            
            @foreach($items as $item_id => $item)
            <tr>
              <td> {{$item['ItemCode']}}</td>
              @foreach($item['items'] as $branch_id => $item_of_branch)
                <td>{{$item_of_branch['daily_sold_qty']}}</td>
                <td>{{$item_of_branch['Mult']}}</td>
                <td>{{$item_of_branch['stock']}}</td>
                <td>{{$item_of_branch['pending']}}</td>
                <td class="blue_box">{{$item_of_branch['QtyPO']}}</td>
              @endforeach
              <td class="red_box">0</td>
              <td class="blue_box">88</td>
              <td> <button class="btn btn-primary"> <span class="fa fa-pencil"></span> </button> </td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </div>

      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="col-md-6">
          <button class="btn btn-default">Back</button>
        </div>
        <div class="col-ms-6 pull-right">
          <button class="btn btn-primary">Save</button>
        </div>

        </div>

      </div>
    
    </div>
  </div>
</div>

</section>

@endsection
