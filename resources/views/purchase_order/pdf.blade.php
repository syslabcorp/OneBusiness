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
    div.page
    {
      page-break-after: always;
      page-break-inside: avoid;
    }
  </style>
</head>
<body>

@while( $index <= $num_page)
<div  class="{{ $index == $num_page ?'' :'page' }}">
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
            <span class="value_po"> {{$index}} </span>
          </div>
        </div>
      
    <div class="po">
      <div class="title">
        <b>Total Price: </b>
      </div>
      <div class="value">
        <span class="value_po"> P{{ $purchase_order->total_amt ? number_format($purchase_order->total_amt, 2, '.', ',')  : "" }} </span>
      </div>
    </div>
    </div>
  </div>

  <table id="main_table">
    <tr>
      @php $colspan = 0; @endphp
      @foreach ( $branchs as $key => $branch)
        @if( (array_search($key, array_keys($branchs->toArray())) + 1) > ( $quantity * ( $index - 1 ) ) && (array_search($key, array_keys($branchs->toArray())) + 1) <= ( $quantity * ( $index ) ) )
          @php $colspan++; @endphp
        @endif
      @endforeach

      @if($index == $num_page)
        <th colspan="{{ ($colspan+2)  }}">
          List Of Orders
        </th>
      @else
        <th colspan="{{ ($colspan+1)  }}">
          List Of Orders
        </th>
      @endif
    </tr>

    <tr>
      <th></th>
      @foreach ( $branchs as $key => $branch)
        @if( (array_search($key, array_keys($branchs->toArray())) + 1) > ( $quantity * ( $index - 1 ) ) && (array_search($key, array_keys($branchs->toArray())) + 1) <= ( $quantity * ( $index ) ) )
          <th>
            {{$branch[0]->branch()->first()->ShortName}}
          </th>
        @endif
      @endforeach
      @if($index == $num_page)
        <th>Total</th>
      @endif
    </tr>
    @foreach( $purchase_order_details as $key_detail => $purchase_order_detail )
      <tr>
        <td class="item_code">{{ $purchase_order_detail[0]->stock_item()->first()->ItemCode }}</td>
        @php $total = 0; @endphp
        
        @foreach ( $branchs as $key => $branch)
          @if( (array_search($key, array_keys($branchs->toArray())) + 1) > ( $quantity * ( $index - 1 ) ) && (array_search($key, array_keys($branchs->toArray())) + 1) <= ( $quantity * ( $index ) ) )
            
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

          @endif
        @endforeach
        @if($index == $num_page)
          <td> {{$total_qty[$key_detail]}} </td>
        @endif
      </tr>
    @endforeach
  </table>
  
   @php $index++ @endphp

    @if($index > $num_page)
    <div class="total">
      <b>Total Qty: </b>
      <span class="value_total_qty"> {{ $purchase_order->tot_pcs ? $purchase_order->tot_pcs : "" }} </span>
    </div>
    @endif
  </div>

  @endwhile

  

</body>
</html>
