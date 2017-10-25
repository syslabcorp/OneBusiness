<table class="table">
    <thead>
        <th>Content</th>
        <th>Copy To</th>
    </thead>
    <tbody>
        @foreach($branch->footers()->orderBy('sort', 'ASC')->get() as $footer)
        <tr>
            <td>
                <form action="{{ route('branchs.footers.update', [$branch, $footer, '#stub-footer']) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <textarea name="content" cols="30" rows="3" class="form-control" placeholder="Content"
                                    {{ \Auth::user()->checkAccess("Stub Footer", "E") ? "" : "readonly" }}>{{ $footer->Foot_Text }}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            @if(\Auth::user()->checkAccess("Stub Footer", "E"))
                            <button class="btn btn-info btn-sm" title="Up" name="sort" value="up"><i class="fa fa-arrow-up"></i></button>
                            <button class="btn btn-info btn-sm" title="Down" name="sort" value="down"><i class="fa fa-arrow-down"></i></button>
                            <button class="btn btn-success btn-sm" title="Save"><i class="fa fa-save"></i></button>
                            @endif
                            @if(\Auth::user()->checkAccess("Stub Footer", "D"))
                            <button class="btn btn-danger btn-sm" name="_method" value="DELETE" title="Delete"><i class="fa fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
            </td>
            <td>
                @if($loop->index == 0 && \Auth::user()->checkAccess("Stub Footer", "E"))
                <form action="{{ route('branchs.footers.copy', [$branch, $footer, '#stub-footer']) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-md-9">
                            <select name="target" class="form-control">
                                <option value="">Select Branch</option>
                                @foreach($branchs as $selectBranch)
                                    <option value="{{ $selectBranch->Branch }}">{{ $selectBranch->ShortName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-sm" title="Copy To Branch"><i class="fa fa-copy"></i></button>
                        </div>
                    </div>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
        @if(\Auth::user()->checkAccess("Stub Footer", "A"))
        <tr>
            <td>
                <form action="{{ route('branchs.footers.store', [$branch, '#stub-footer']) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-10">
                            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                                <textarea name="content" id="" cols="30" rows="3" class="form-control" placeholder="Content"></textarea>
                                @if($errors->has('content'))
                                    <span class="help-block">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <button class="btn btn-success btn-sm" title="Add"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        @endif
    </tbody>
</table>

<div class="col-md-12" style="margin-bottom: 15px;">
    <hr>
    <a href="{{ route('branchs.index', ['corpID' => $branch->corp_id]) }}" class="btn btn-default pull-left">
        <i class="fa fa-reply"></i> Back
    </a>
</div>