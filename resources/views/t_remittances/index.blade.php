@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>CRR COLLECTION</h4>
                
              </div>
              <div class="col-xs-3">
                <div class="pull-right">
                  <a href="aaaa">Add Collection</a>
                  
                </div> 
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row">
              <form class="col-xs-3 pull-right" method="GET">
                  <select name="status" class="form-control" >
                    <option value="checked">Checked</option>
                    <option value="unchecked">Unchecked</option>
                  </select>
              </form>
            </div>
            <form action="">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th >TXN No.</th>
                    <th>Date/Time</th>
                    <th>Pick-up Teller</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  @foreach($remittances as $remittance)
                    <tr class="text-center">
                      <td>{{ $remittance->txn_id }}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>

                        <a href="{{ route('branch_remittances.show', [$remittance]) }}" style="margin-right: 10px;" 
                          class="btn btn-success btn-xs"
                          title="View">
                          <i class="fa fa-eye"></i>
                        </a>

                        <a href="{{ route('branch_remittances.edit', [$remittance]) }}" style="margin-right: 10px;" 
                          class="btn btn-primary btn-xs"
                          title="Edit">
                          <i class="fa fa-pencil"></i>
                        </a>

                        <a href="{{ route('branch_remittances.destroy', [$remittance]) }}" style="margin-right: 10px;" 
                          class="btn btn-danger btn-xs"
                          title="Delete">
                          <i class="fa fa-trash"></i>
                        </a>

                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </form>
          </div>
          
        </div>
      </div>
    </div>
</section>
@endsection