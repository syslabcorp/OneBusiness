@extends('layouts.custom')

@section('content')
  <div class="rown">
    <div class="col-sm-6" >
      <form action="{{ route('wage-templates.edit-contract', [$template, 'corpID' => request()->corpID]) }}" method="POST">
        {{ csrf_field() }}
        <textarea name="contract" rows="10" id="contractEditor" value="{{ $template->contract }}">{{ $template->contract }}</textarea>
        <div class="rown" style="margin-top: 15px;">
          <div class="col-sm-6">
            <a class="btn btn-default" href="{{ route('wage-templates.index', ['corpID' => request()->corpID]) }}">
              <i class="fas fa-reply"></i> Back
            </a>
          </div>
          <div class="col-sm-6 text-right">
            <a class="btn btn-info btn-preview" href="{{ route('wage-templates.preview-contract', [$template, 'corpID' => request()->corpID]) }}"
              target="_blank">
              <i class="fas fa-eye"></i> Preview PDF
            </a>
            <button class="btn btn-success">
              <i class="fas fa-save"></i> Save
            </button>
          </div>
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
    CKEDITOR.plugins.addExternal('justify', '{{ URL('/public/plugins/justify') }}/', 'plugin.js' );

    (() => {
      CKEDITOR.replace('contractEditor', {
        extraPlugins: 'justify'
      })
      CKEDITOR.instances.contractEditor.on('change', (event) => {
        $('.btn-preview').addClass('disabled')
        $('.previewEditor').html(event.editor.getData())
      });
    })()
  </script>
@endsection