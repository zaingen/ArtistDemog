@extends('master.master')
@push('css')
 <style>
    .resultset{
        border: 1px solid #ededed;
        padding: 10px;
        border-radius: 5px;
    }
    #search-form, .form-control {
        margin-bottom: 20px;
    }
    .album-wrap{
        float: left;
        width: 30%;
        margin: 10px 0;
    }
    .album-detail{
        margin: 20px 0;
    }
    .album-detail .ablum-name,.album-detail .ablum-track{
        display: inline-block;
    }
    .album-detail .ablum-name {
        width: 50%
    }
    .album-detail .ablum-track{
        text-align: right;
    }
    .cover {
        width: 300px;
        height: 300px;
        display: inline-block;
        background-size: cover;
    }
    .cover:hover {
        cursor: pointer;
    }
    .cover.playing {
        border: 5px solid #e45343;
    }
 </style>
@endpush
@section('content')
<div id="page-content-wrapper" class="">
    <div class="row">
        <div class="col-md-9 col-md-offset-2">
            @if(Session::has('message'))
                <p class="{{ Session::get('alert-class') }}">{{ Session::get('message') }}</p>
            @endif    
            <div class="">
                <h1>Search for an Artist</h1>
                <p>Type an artist name and click on "Search". Then, click on any album from the results to play 30 seconds of its first track.</p>
                <form id="search-form">
                    <input type="text" id="artist" value="" class="form-control" placeholder="Type an Artist Name"/>
                    <input type="submit" id="search" class="btn btn-primary" value="Search" />
                </form>
                <div id="results"></div>
            </div>
            <?php /*
            <script id="results-template" type="text/x-handlebars-template">
                @{{#each albums.items}}
                    <div class="album-wrap">
                        <div class='album-image' style="background-image:url(@{{images.0.url}})" data-album-id="@{{id}}" class="cover"></div>
                        <div class="album-detail">
                            <span class="ablum-name"></span>
                            <span class="ablum-track"></span>
                        </div>
                    </div>
                @{{/each}}
                <div class="row">
                    <ul class="pagination">
                        @{{#if albums.next}}
                              <li><a href="#" onclick="getResult('@{{albums.next}}')">Next Result</a></li>
                        @{{/if}}
                        @{{#if albums.previous}}
                              <li><a href="#" onclick="getResult('@{{albums.previous}}')">Previous Result</a></li>
                        @{{/if}}
                    </ul>                
                </div>
            </script> 
            */
            ?>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="<?php echo url('/'); ?>/js/tags.js" ></script>
<script type="text/javascript">
    $( "#artist" ).autocomplete({
      source: availableTags,
      list: {   
        match: {
          enabled: true
        }
      }
    });
    /// find template and compile it
        //templateSource = document.getElementById('results-template').innerHTML;
        //template = Handlebars.compile(templateSource);
        resultsPlaceholder = document.getElementById('results');
        playingCssClass = 'playing';
        audioObject = null;
        //console.log(templateSource);
        //console.log(template);

    var fetchTracks = function (albumId, callback) {
        $.ajax({
            url: 'https://api.spotify.com/v1/albums/' + albumId,
            success: function (response) {
                callback(response);
            }
        });
    };
    var getResult=function(queryurl){
        event.preventDefault();
        //console.log(queryurl);
        $.ajax({
            url: queryurl,
            success: function (response) {
                //console.log(response);
                resultsPlaceholder.innerHTML="";
                //resultsPlaceholder.innerHTML = template(response);
                resultsPlaceholder.innerHTML =populateResultData(response);
            }
        });
    };
    var populateResultData=function(data){
        var album_wrap_html="";
        //console.log(data);
        if(data.albums.total ==0){
            album_wrap_html+="<div class='message'> No Results Found .</div>";
            return album_wrap_html;
        }
        $.each( data, function( albums, albumdata ) {
            var count=0;
            album_wrap_html+="<div class='row'>";
            //console.log(albumdata);
            $.each( albumdata.items, function( items, itemresults ) {
                count++;
                album_wrap_html+="<div class='album-wrap'>";
                    album_wrap_html+="<div data-album-id='"+itemresults.id+"' class='album-image cover' style='background-image:url("+itemresults.images[0].url+")'></div>";
                    album_wrap_html+="<div class='album-detail'>";
                        album_wrap_html+="<span class='ablum-name'>";
                            album_wrap_html+=itemresults.name;
                        album_wrap_html+="</span>";
                        album_wrap_html+="<span class='ablum-track'>";
                            //album_wrap_html+="<a class='ablum-track' href='<?php //echo url('/');?>/albumtracks?albumid="+itemresults.id+"'>";
                            album_wrap_html+="<a class='ablum-track' href='#'>";
                                album_wrap_html+="Album Tracks";
                            album_wrap_html+="</a>";
                        album_wrap_html+="</span>";
                    album_wrap_html+="</div>";
                album_wrap_html+="</div>";
                if(count===3){
                    count=0;
                    album_wrap_html+="</div><div class='row'>";
                }
                /*var album_wrap = $('<div></div>').addClass('album-wrap');
                var album_image = $('<div></div>').addClass('album-image');
                //$(album_image).css("background-image", 'url(' + itemresults.images.0.url + ')');  
                $(album_image).addClass('cover');
                $(album_image).addClass('cover');
                $(album_image).attr('data-album-id', itemresults.id);
                $(album_wrap).append(album_image);
                var album_detail = $('<div></div>').addClass('album-detail');
                var album_name = $('<span></span>').addClass('ablum-name').text(itemresults.name);
                $(album_detail).append(album_name);
                var album_track = $('<a></a>').addClass('ablum-track').attr("herf",itemresults.uri).text("Album Tracks");
                $(album_detail).append(album_track);
                $(album_wrap).append(album_detail);
                console.log(album_wrap[0]);
                album_wrap_html+=album_wrap[0];*/
            });
            if(count <= 3){
                album_wrap_html+="</div>";
            }
            //console.log(album_wrap_html);
        });
        album_wrap_html+="<div style='text-align:center;'>";
        album_wrap_html+="<ul class='pagination'>";
        if(data.albums.previous){
            var prevlink='"'+data.albums.previous+'"';
            album_wrap_html+="<li><a href='#' onclick='getResult("+prevlink+")'>Previous Result</a></li>";
        }
        if(data.albums.next){
            var nextlink='"'+data.albums.next+'"';
            album_wrap_html+="<li><a href='#' onclick='getResult("+nextlink+")'>Next Result</a></li>";
        }
        album_wrap_html+="</ul>";
        album_wrap_html+="</div>";
        //console.log(album_wrap_html);
        return album_wrap_html;
    }

    var searchAlbums = function (artist) {
        $.ajax({
            url: 'https://api.spotify.com/v1/search',
            data: {
                q: artist,
                type: 'album'
            },
            success: function (response) {
                //console.log(response);
                //resultsPlaceholder.innerHTML = template(response);
                resultsPlaceholder.innerHTML =populateResultData(response);
            }
        });
    };

    results.addEventListener('click', function (e) {
        var target = e.target;
        if (target !== null && target.classList.contains('cover')) {
            if (target.classList.contains(playingCssClass)) {
                audioObject.pause();
            } else {
                if (audioObject) {
                    audioObject.pause();
                }
                fetchTracks(target.getAttribute('data-album-id'), function (data) {
                    audioObject = new Audio(data.tracks.items[0].preview_url);
                    audioObject.play();
                    target.classList.add(playingCssClass);
                    audioObject.addEventListener('ended', function () {
                        target.classList.remove(playingCssClass);
                    });
                    audioObject.addEventListener('pause', function () {
                        target.classList.remove(playingCssClass);
                    });
                });
            }
        }
    });

    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        searchAlbums(document.getElementById('artist').value);
    }, false);
    /*(function() {
        function login(callback) {
            var CLIENT_ID = '7cd48e041ecf48adabebf07eb6f03caa';
            var REDIRECT_URI = 'http://localhost/myspotify/spotify.php';
            function getLoginURL(scopes) {
                return 'https://accounts.spotify.com/authorize?client_id=' + CLIENT_ID +
                  '&redirect_uri=' + encodeURIComponent(REDIRECT_URI) +
                  '&scope=' + encodeURIComponent(scopes.join(' ')) +
                  '&response_type=token';
            }
            alert(CLIENT_ID);
            
            var url = getLoginURL([
                'user-read-email'
            ]);
            
            var width = 450,
                height = 730,
                left = (screen.width / 2) - (width / 2),
                top = (screen.height / 2) - (height / 2);
        
            window.addEventListener("message", function(event) {
                var hash = JSON.parse(event.data);
                if (hash.type == 'access_token') {
                    callback(hash.access_token);
                }
            }, false);
            
            var w = window.open(url,
                'Spotify',
                'menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left
               );
        }

        function getUserData(accessToken) {
            return $.ajax({
                url: 'https://api.spotify.com/v1/me',
                headers: {
                   'Authorization': 'Bearer ' + accessToken
                }
            });
        }

        var templateSource = document.getElementById('result-template').innerHTML,
            template = Handlebars.compile(templateSource),
            resultsPlaceholder = document.getElementById('result'),
            loginButton = document.getElementById('btn-login');
        
        loginButton.addEventListener('click', function() {
            login(function(accessToken) {
                getUserData(accessToken)
                    .then(function(response) {
                        loginButton.style.display = 'none';
                        alert(response);
                        resultsPlaceholder.innerHTML = template(response);
                    });
                });
        });
})();
*/    
</script>
@endpush

