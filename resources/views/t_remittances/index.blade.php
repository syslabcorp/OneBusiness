@extends('layouts.custom')

@section('content')
@php
  $verifyType = 0;
  if(\Auth::user()->bio_auth != 1) {
    if(\Auth::user()->otp_auth == 1) {
      $verifyType = 2;
    }else {
      $verifyType = 1;
    }
  }
@endphp
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
                  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 22, 'A'))
                  <a href="{{ route('branch_remittances.create', $queries) }}">Add Collection</a>
                  @endif
                </div> 
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row" style="margin-bottom: 20px;">
              <form class="col-xs-3 pull-right">
                <select name="status" class="form-control" id="filter-status">
                  <option value="all">All</option>
                  <option value="checked" {{ $queries['status'] == "checked" ? "selected" : "" }}>Checked</option>
                  <option value="unchecked" {{ $queries['status'] == "unchecked" ? "selected" : "" }} >Unchecked</option>
                </select>
              </form>
            </div>
            <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                  <th >TXN No.</th>
                  <th>Date/Time</th>
                  <th>Pick-up Teller</th>
                  <th>Subtotal</th>
                  <th>Status</th>
                  <th>Last Updated by</th>
                  <th>Action</th>
                </tr>
                @foreach($collections as $collection)
                  <tr class="text-center">
                    <td>{{ $collection->ID }}</td>
                    <td>{{ $collection->CreatedAt->format('Y-m-d H:ia') }}</td>
                    <td>{{ $collection->user->UserName }}</td>
                    <td>{{ number_format($collection->Subtotal, 2) }}</td>
                    <td>
                      <form class="form-horizontal" method="POST" action="" role="form">
                        {{ csrf_field() }}
                        <input type="hidden" name="redirect" value=""/>
                        <input type="hidden" name="corpID" value="{{ $corpID }}">
                        <input type="checkbox" name="status" class="{{ \Auth::user()->checkAccessByIdForCorp($corpID, 22, 'E') ? "col-status" : "" }}" onclick="return false;" data-id="{{ $collection->ID }}"
                        {{ $collection->Status == 1 ? "checked" : ""}}>
                      </form>
                    </td>
                    <td>
                      @if($collection->updatedBy)
                      {{ $collection->updatedBy->UserName }} </br>
                      ({{ $collection->UpdatedAt->format('Y-m-d H:ia') }})
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('branch_remittances.show', array_merge([$collection], ['corpID' => $corpID])) }}" style="margin-right: 10px;" 
                        class="btn btn-success btn-xs {{ \Auth::user()->checkAccessByIdForCorp($corpID, 15, 'V') ? "" : "disabled" }}"
                        title="View">
                        <i class="fa fa-eye"></i>
                      </a>

                      <a href="{{ route('branch_remittances.edit', array_merge([$collection], ['corpID' => $corpID])) }}" style="margin-right: 10px;" 
                        class="btn btn-primary btn-xs {{ \Auth::user()->checkAccessByIdForCorp($corpID, 22, 'E') ? "" : "disabled" }}"
                        title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                      </a>

                      <form action="{{ route('branch_remittances.destroy', array_merge([$collection], ['corpID' => $corpID])) }}" method="POST"
                        style="display: inline-block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button style="margin-right: 10px;"  title="Delete" data-id="{{ $collection->ID }}"
                        class="btn btn-danger btn-xs {{ \Auth::user()->checkAccessByIdForCorp($corpID, 22, 'D') ? "" : "disabled" }}" >
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
                @if(!$collections->count())
                <tr>
                  <td colspan="7">No collections</td>
                </tr>
                @endif
              </tbody>
            </table>

            <div class="row">
              <div class="col-md-6">
                <form class="" id="date_range" action="{{ route('branch_remittances.index', ['corpID' => $corpID]) }}" method="GET">
                  <input type="hidden" name="corpID" value="{{$corpID}}">
                  <div class="checkbox col-xs-12">
                    <label for="view_date_range" class="control-label">
                      <input type="hidden" value="{{ $queries['status'] }}" name="status">
                      <input type="hidden" value="0" name="view_date_range">
                      <input type="checkbox" {{$start_date || $end_date ? 'checked': ""}} id="view_date_range" value="1"
                        name="view_date_range">
                      View by Date Range
                    </label>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xs-5">
                        <input type="date" name="start_date" id="start_date" {{ $start_date || $end_date ? '': 'disabled="true"' }} class="form-control datepicker " value="{{$start_date}}">
                      </div>
                      <div class="col-xs-5">
                        <input type="date" name="end_date" id="end_date"  {{ $start_date || $end_date ? '': 'disabled="true"' }}  class="form-control datepicker"  value="{{$end_date}}">
                      </div>
                      <div class="col-xs-2">
                        <button id="button_ranger_date" {{ $start_date || $end_date ? '': 'disabled="true"' }} class="btn btn-primary">Show</button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xs-12">
                        <a href="/OneBusiness/home" class="btn btn-default">
                          <i class="fa fa-reply"></i> Back
                        </a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection

<div id="modal-confirm-password" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" action="" role="form" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="redirect" value=""> 
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="verify-password">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirm Password</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" style="display: none;"></div>
            <div class="form-group">
              <div class="row">
                <label for="" class="col-xs-3">Your Password</label>
                <div class="col-xs-9">
                  <input type="password" name="password" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="verify-otp" style="display: none;">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">OTP</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" style="display: none;"></div>
            <div class="form-group">
              <div class="row">
                <label for="" class="col-xs-3">Your otp</label>
                <div class="col-xs-9">
                  <input type="text" name="otp" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary btn-verify" type="button">Verify</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('pageJS')
<script type="text/javascript">
  var verifyStep = {{ $verifyType }};
  var _token = $("input[name='_token']").val();

  if(verifyStep == 2) {
    $('#modal-confirm-password .verify-password').css('display', 'none');
    $('#modal-confirm-password .verify-otp').css('display', 'block');
  }
  $('.table').on("click", ".col-status", function(event) {
    var url = '/branch_remittances/' + $(this).attr('data-id') +  '/remittances';

    if(window.location.pathname.match(/OneBusiness/)) {
      url = '/OneBusiness' + url; 
    }

    if(event.target.checked) {
      $selfForm = $(this).parents('form');
      $selfForm.attr('action', url)
      $selfForm.find('input[name="redirect"]').val(window.location.href);
      $selfForm.submit();
      return;
    }

    if(verifyStep == 0) {
      return;
    }

    $('#modal-confirm-password .alert-danger').css('display', 'none');
    $('#modal-confirm-password input[name="password"]').val("");
    $('#modal-confirm-password input[name="otp"]').val("");
    $('#modal-confirm-password form').attr('action', url)
    $('#modal-confirm-password input[name="redirect"]').val(window.location.href);
    if(verifyStep == 2) {
      $.ajax({
        url: "{{ route('users.generateOTP') }}",
        type: "POST",
        data: {_token},
        success: function(res) {
          if(res.success) {
            $('#modal-confirm-password').modal({ backdrop: 'static', keyboard: false });
            $('#modal-confirm-password').modal("show");
          }else {
            toastr.error(res.message);
          }
        },
        error: function(res) {
          toastr.error("Can't generate OTP");
        }
      });
    }else {
      $('#modal-confirm-password').modal("show");
    }
    
  });

  $('#modal-confirm-password').on("click", '.btn-verify', function(event) {
    self = $(this);

    if(verifyStep == 1) {
      $.ajax({
        url: "{{ route('users.verifyPassword') }}",
        type: "POST",
        data: { password: $('#modal-confirm-password input[name="password"]').val(), _token},
        success: function(res) {
          if(res.success) {
            self.parents('form').submit();
          }else {
            $('#modal-confirm-password .alert-danger').html("Incorrect password.").slideDown(500).delay(3000).slideUp(400);
          }
        },
        error: function(res) {
          $('#modal-confirm-password .alert-danger').html("Incorrect password.").slideDown(500).delay(3000).slideUp(400);
        }
      });
    }else if(verifyStep == 2) {
      $.ajax({
        url: "{{ route('users.verifyOTP') }}",
        type: "POST",
        data: {otp: $('#modal-confirm-password input[name="otp"]').val(), _token},
        success: function(res) {
          if(res.success) {
            self.parents('form').submit();
          }else {
            $('#modal-confirm-password .alert-danger').html(res.message).slideDown(500).delay(3000).slideUp(400);
          }
        },
        error: function(res) {
          $('#modal-confirm-password .alert-danger').html("Please enter correct OTP.").slideDown(500).delay(3000).slideUp(400);
        }
      });
    }
  });

  $('form').on("click", ".btn-danger", function(event){
    event.preventDefault();
    var collectionID = $(this).attr('data-id');
    var self = $(this);
    swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>Are you sure you want to delete Collection <strong>#" + collectionID + "?</strong></div>",
        html:  true,
        customClass: 'swal-wide',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Delete',
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm){
      if (isConfirm){
        self.parents('form').submit();
      }
    });
  });

  $('#filter-status').change(function(event) {
    window.location = window.location.pathname + window.location.search.replace(/status=\w*&/g, "").replace(/&status=\w*/g, "") + "&status=" + $(this).val();
  });
</script>
@endsection