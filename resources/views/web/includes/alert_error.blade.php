@if ($message = Session::get('error'))
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  	<strong><i class="fa fa-exclamation"></i> &nbsp;Error!</strong> {{$message}}.
</div>
@endif