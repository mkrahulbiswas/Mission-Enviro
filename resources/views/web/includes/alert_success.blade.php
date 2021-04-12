@if ($message = Session::get('success'))
<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  	<strong><i class="fa fa-check"></i> &nbsp;Success!</strong> {{$message}}.
</div>
@endif
<!-- <div class="notifyjs-corner" style="top: 0px; right: 0px;"><div class="notifyjs-wrapper notifyjs-hidable">
    <div class="notifyjs-arrow"></div>
    <div class="notifyjs-container" style=""><div class="notifyjs-metro-base notifyjs-metro-success"><div class="image" data-notify-html="image"><i class="fa fa-check"></i></div><div class="text-wrapper"><div class="title" data-notify-html="title">Success</div><div class="text" data-notify-html="text">{{$message}}</div></div></div></div>
</div></div> -->