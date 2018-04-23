<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    #main_table{
      width: 100%;
      text-align: center;
      border-collapse: collapse;
      border: 1px solid black;
      margin-bottom: 20px;
    }

    #main_table th, td {
      border: 1px solid black;
    }

    #main_table th{
      padding-top: 5px;
      padding-bottom: 5px;
    }

    .item_code{
      width: 150px;
      text-align: left;
    }

    .total_qty{
    }
    .value_total_qty{
      border-bottom: 1px solid black;
      text-align: center;
      padding-left: 40px;
      padding-right: 40px;
      display: inline-block;
    }
    .total{
      float: right;
    }

    .row{
      width: 100%;
    }
    .one-row{
      width: 40%;
      display: inline-block;
      
    }
    .mid-row{
      width: 20%;
      display: inline-block;
    }

    .po{
      width: 100%;
      margin-bottom: 10px;
    }

    .title{
      width: 40%;
      display: inline-block;
    }

    .value{
      width: 50%;
      display: inline-block;
      text-align: center;
      border-bottom: 1px solid black;
    }
  </style>
</head>
<body>

  <div class="row">
      <div class="one-row">
        <div class="po">
          <div class="title">
            <b>P.O.#: </b>
          </div>
          <div class="value">
            <span class="value_po"> {{ $purchase_order->po_no }} </span>
          </div>
        </div>
        
      <div class="po">
        <div class="title">
          <b>Date: </b>
        </div>
        <div class="value">
          <span class="value_po"> {{  $purchase_order->po_date->format("d/m/Y") }} </span> 
        </div>
      </div>

      </div>

      <div class="mid-row">
      
      </div>

      <div class="one-row">
        <div class="po">
          <div class="title">
            <b>Page #: </b>
          </div>
          <div class="value">
            <span class="value_po"> 1 </span>
          </div>
        </div>
      
    <div class="po">
      <div class="title">
        <b>Total Price: </b>
      </div>
      <div class="value">
        <span class="value_po"> {{ $purchase_order->total_amt ? number_format($purchase_order->total_amt, 2, '.', ',')  : "" }} </span>
      </div>
    </div>
    </div>
  </div>
  
  <table id="main_table">
    <tr>
      <th colspan="{{ ( count($branchs) + 2 ) }}">
        List Of Orders
      </th>
    </tr>

    <tr>
      <th></th>
      @foreach ( $branchs as $branch)
        <th>
          {{$branch[0]->branch()->first()->ShortName}}
        </th>
      @endforeach
      <th>Total</th>
    </tr>
    @foreach( $purchase_order_details as $purchase_order_detail )
      <tr>
        <td class="item_code">{{ $purchase_order_detail[0]->stock_item()->first()->ItemCode }}</td>
        @php $total = 0; @endphp
        
        @foreach ( $branchs as $key => $branch)
          
          @php $check = false; @endphp

          @foreach( $purchase_order_detail as $detail )
            
            @if( $detail->Branch == $key )
              <td>
                {{$detail->Qty}}
                @php $total += $detail->Qty; @endphp
              </td>
              @php $check = true; @endphp
            @endif
          @endforeach

          @if(!$check)
            <td> - </td>
          @endif

        
        @endforeach
        <td> {{$total}} </td>
      </tr>
    @endforeach
  </table>

  <div class="total">
    <b>Total Qty: </b>
    <span class="value_total_qty"> {{ $purchase_order->tot_pcs ? $purchase_order->tot_pcs : "" }} </span>
  </div>
</body>
</html>
