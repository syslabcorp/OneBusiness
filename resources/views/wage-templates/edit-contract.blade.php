@extends('layouts.custom')

@section('content')
  <div class="rown">
    <div class="col-sm-6" >
      <form action="{{ route('wage-templates.edit-contract', [$template, 'corpID' => request()->corpID]) }}" method="POST">
        {{ csrf_field() }}
        <textarea name="contract" rows="10" id="contractEditor" value="{{ $template->contract }}">{{ $template->contract }}</textarea>
        <div class="form-group">
          <button class="btn btn-success" style="margin-top: 15px;">
            Update
          </button>
        </div>
      </form>
    </div>
    <div class="col-sm-6 previewEditor" style="border: 1px solid #ccc; padding: 15px;border-radius: 5px;">
      {!! $template->contract !!}
    </div>
  </div>
@endsection

@section('pageJS')
<script src="https://cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>

  <script type="text/javascript">
    (() => {
      CKEDITOR.replace('contractEditor')
      CKEDITOR.instances.contractEditor.on('change', (event) => { 
        $('.previewEditor').html(event.editor.getData())
      });
    })()
  </script>
@endsection