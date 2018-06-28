<div class="tab-pane fade {{ $tab == 'auto' ? 'active in' : '' }} in" id="personInfo" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V'))
    <div class="row">
      <form class="form form-horizontal" action="">
        <div class="col-md-8">
          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                First Name
              </label>
            </div>
            <div class="col-md-6">
              <input type="text" name="name" id="" class="form-control" value="{{$user->FirstName}}" disabled>
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
              <input type="text" name="name" id="" class="form-control" value="{{$user->MiddleName}}" disabled>
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
              <input type="text" name="name" id="" class="form-control" value="{{$user->LastName}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                Suffix
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" name="suffix" id="" value="{{$user->SuffixName}}" class="form-control" disabled>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="control-label">Address</label>
            </div>
            <div class="col-md-10">
              <input type="text" name="{{$user->Address}}" id="" class="form-control" disabled="">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Position:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->Position}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                TIN #:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->TIN}}" disabled>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Sex:
              </label>
            </div>
            <div class="col-md-4">
              <select name="sex" id="" class="form-control" disabled>
                <option value="male" {{$user->sex == 'Male' ? checked: ""}} >Male</option>
                <option value="female" {{$user->sex == 'Female' ? checked: ""}}>Female</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                SSS #:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->SSS}}" disabled>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Birthday:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->Bday ? $user->Bday->format('d/m/Y') : ""}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                PHIC #:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->PHIC}}" disabled>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label for="" class="label-control">
                Bank Acct #:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->acct_no}}" disabled>
            </div>
            <div class="col-md-2">
              <label for="" class="label-control">
                HDMF #:
              </label>
            </div>
            <div class="col-md-4">
              <input type="text" name="position" id="" class="form-control" value="{{$user->Pagibig}}" disabled>
            </div>
          </div>

        </div>
        <div class="col-md-4">
          <div class="row">
            <button class="btn btn-primary">Edit</button>
          </div>

          <div class="row form-group" style="border: 1px solid #ddd; margin: 1px; padding: 10px; border-radius: 5px;">
            <div class="col-md-6">
              <label for="" class="control-label">
                <input type="radio" name="split_type" id="" {{$user->split_type == "O" ? "checked" : ""}}>
                I
              </label>
            </div>
            <div class="col-md-6">
              <label for="" class="control-label">
                <input type="radio" name="split_type" {{$user->split_type == "O" ? "checked" : ""}} id="">
                O
              </label>
            </div>
          </div>

          <div class="row" style="border: 1px solid #ddd; margin: 1px;  min-height: 250px; border-radius: 5px;">
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
