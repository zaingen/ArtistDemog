@extends('master.master')
@section('content')
<div id="page-content-wrapper" class="">
    <div class="row">
        <div class="col-md-9 col-md-offset-2">
            @if(Session::has('message'))
                <p class="{{ Session::get('alert-class') }}">{{ Session::get('message') }}</p>
            @endif    
	        <div class="result_wrap">
	        	<?php var_dump($album_tracks);?>
	        	
	        </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
	var albumid='<?php echo $album_id; ?>';
	console.log('welcome to the album tracks page');
	var getAlbumTracks = function (albumid) {
        $.ajax({
            url: 'https://api.spotify.com/v1/albums/'+albumid+'/tracks',
            success: function (response) {
            	console.log(response);
            }
        });
    };
    //getAlbumTracks(albumid);
});
</script>
@endpush



