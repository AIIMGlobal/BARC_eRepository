@if(Session::has('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h4><i class="icon fa fa-check"></i> {{ __('Error!') }}</h4>
    {{ __(Session::get('error')) }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  {{-- <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> {{ __('Alert!') }}</h4>
    {{ __(Session::get('error')) }}
  </div> --}}
@endif

@if(Session::has('info'))
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-info"></i> {{ __('Error!') }}</h4>
    {{ __(Session::get('info')) }}
  </div>
@endif

@if(Session::has('warning'))
  <div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-exclamation-triangle"></i> {{ __('Warning!') }}</h4>
    {{ __(Session::get('warning')) }}
  </div>
@endif

@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h4><i class="icon fa fa-check"></i> {{ __('Success..!') }}</h4>
        {{ __(Session::get('success')) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif
</div>