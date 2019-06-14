@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-info" role="alert">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ session('status') }}</label>
                                @if(session('newpass'))
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input id="newPass" type="text" class="form-control" value="{{ session('newpass') }}" readonly>
                                            <div class="input-group-append">
                                                <button id="btnTooltip" type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Copy" onclick="copyPassword()"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="/reset-password">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
function copyPassword() {
    var copyText = document.getElementById("newPass");
    copyText.select();
    document.execCommand('copy');

    $('#btnTooltip')
        .tooltip('hide')
        .attr('data-original-title', 'Copied')
        .tooltip('show');
}
$(function () {
  $('#btnTooltip').tooltip()
})
</script>
@endpush