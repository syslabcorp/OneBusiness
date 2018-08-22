<div class="tab-pane fade {{ $tab == 'auto' ? 'active in' : '' }} in" id="personInfo" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V') || true)
    <div class="row">
      <form class="form form-horizontal" action="{{ route('employee.update', [$user, 'corpID' => $corpID]) }}" id="employee_form" method="POST">
        {{ method_field('patch') }}
        {{ csrf_field() }}
        <div class="col-md-8">
          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                First Name
              </label>
            </div>
            <div class="col-md-6">
              <div class="rown">
                <div class="col-xs-10">
                  <input type="text" name="FirstName" class="form-control disabled" value="{{$user->FirstName}}" disabled>
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-default btn-md btnModalImage" type="button" data-image="{{ $user->getImageFile(request()->corpID, 1, 7) }}">
                    <i class="fas fa-image"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-4"></div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Middle Name
              </label>
            </div>
            <div class="col-md-6">
              <input type="text" name="MidName" class="form-control disabled" value="{{$user->MidName}}" disabled>
            </div>
            <div class="col-md-4"></div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Last Name
              </label>
            </div>
            <div class="col-md-6">
              <input type="text" name="LastName" class="form-control disabled" value="{{$user->LastName}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                Suffix
              </label>
            </div>
            <div class="col-md-2">
              <select type="text" name="SuffixName" class="form-control disabled" disabled>
                <option value=""></option>
                @foreach (['JR', 'SR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'] as $value)
                  <option value="{{ $value }}" {{ $user->SuffixName == $value ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="control-label">Address</label>
            </div>
            <div class="col-md-10">
              <div class="rown">
                <div class="col-xs-10">
                  <input type="text" name="Address" value="{{$user->Address}}" id="" class="form-control disabled" disabled="">
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-default btn-sm btnModalImage" type="button" data-image="{{ $user->getImageFile(request()->corpID, 1, 2) }}">
                    <i class="fas fa-image"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Position:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="Position" class="form-control disabled" value="{{$user->Position}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                TIN #:
              </label>
            </div>
            <div class="col-md-4">
            <div class="rown">
                <div class="col-xs-10">
                  <input type="text" name="TIN" class="form-control disabled" value="{{$user->TIN}}" disabled>
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-default btn-sm btnModalImage" type="button" data-image="{{ $user->getImageFile(request()->corpID, 1, 3) }}">
                    <i class="fas fa-image"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Sex:
              </label>
            </div>
            <div class="col-md-4">
              <select name="Sex" class="form-control disabled" disabled>
                <option value="Male" {{ $user->Sex == 'Male' ? 'selected' : "" }} >Male</option>
                <option value="Female" {{ $user->Sex == 'Female' ? 'selected' : ""}}>Female</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                SSS #:
              </label>
            </div>
            <div class="col-md-4">
            <div class="rown">
                <div class="col-xs-10">
                  <input type="text" name="SSS" id="" class="form-control disabled" value="{{$user->SSS}}" disabled>
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-default btn-sm btnModalImage" type="button" data-image="{{ $user->getImageFile(request()->corpID, 1, 4) }}">
                    <i class="fas fa-image"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Birthday:
              </label>
            </div>
            <div class="col-md-4">
              <input type="date" name="Bday" class="form-control disabled" value="{{ $user->Bday ? $user->Bday->format('Y-m-d') : ""}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                PHIC #:
              </label>
            </div>
            <div class="col-md-4">
            <div class="rown">
                <div class="col-xs-10">
                  <input type="text" name="PHIC" id="" class="form-control disabled" value="{{$user->PHIC}}" disabled>
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-default btn-sm btnModalImage" type="button" data-image="{{ $user->getImageFile(request()->corpID, 1, 5) }}">
                    <i class="fas fa-image"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Bank Acct #:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="acct_no" id="" class="form-control disabled" value="{{$user->acct_no}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                HDMF #:
              </label>
            </div>
            <div class="col-md-4">
            <div class="rown">
                <div class="col-xs-10">
                  <input type="text" name="Pagibig" id="" class="form-control disabled" value="{{$user->Pagibig}}" disabled>
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-default btn-sm btnModalImage" type="button" data-image="{{ $user->getImageFile(request()->corpID, 1, 6) }}">
                    <i class="fas fa-image"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col-md-4">
          <div class="row">
            <button type="button" class="btn btn-primary" id="edit_employee">Edit</button>
          </div>

          <div class="row form-group" style="border: 1px solid #ddd; margin: 1px; padding: 10px; border-radius: 5px;">
            <div class="col-md-6">
              <label class="control-label">
                <input type="radio" name="split_type" value="I" {{$user->split_type == "I" ? "checked" : ""}} disabled>
                I
              </label>
            </div>
            <div class="col-md-6">
              <label class="control-label">
                <input type="radio" name="split_type" value="O" {{$user->split_type == "O" ? "checked" : ""}} disabled>
                O
              </label>
            </div>
          </div>

          <div class="row" style="justify-content: center;align-items: center; border: 1px solid #ddd; margin: 1px;  min-height: 250px; border-radius: 5px; overflow: hidden; display: flex;">
            <div class="image">
              <div id="loader">

              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  @else
  <div class="alert alert-danger no-close">
    You don't have permission
  </div>
  @endif
</div>
