@extends('layout.next')

@section('title')
  <title>Upload - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')
  <link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
  <li>
    <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">Torrents</span>
    </a>
  </li>
  <li>
    <a href="{{ url('/upload') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">Upload</span>
    </a>
  </li>
@endsection

@section('content')
  @if(!\App\Policy::canUpload($user))
    <div class="container">
      <div class="jumbotron shadowed">
        <div class="container">
          <h1 class="mt-5 text-center">
            <i class="fa fa-times text-danger"></i> Error: Your Upload Rights Have Been Disabled
          </h1>
          <div class="separator"></div>
          <p class="text-center">If You Feel This Is In Error, Please Contact Staff!</p>
        </div>
      </div>
    </div>
  @else
    @if(isset($parsedContent))
      <div class="torrent box container">
        <center><h2>Description Preview</h2></center>
        <div class="preview col-md-12"> @emojione($parsedContent)</div>
        <hr>
      </div>
    @endif

    <div class="upload">
      <div class="block block--danger mbox mbox--bottom">
        <div class="block__title">Before Uploading</div>
        <div class="block__content flex">
          <i class="danger-symbol fa fa-exclamation-triangle"></i>
          <div class="flex__expanded">
            Please use <a href="{{ route('announce', ['passkey' => $user->passkey]) }}">{{ route('announce', ['passkey' => $user->passkey]) }}</a>
            as URL when creating a new torrent. If you want to use your torrent without downloading it from the site you need
            to set the private flag and the source to {{config('torrent.source')}}. TMDB or IMDB is required for all
            uploads when available! It is used to grab posters/backdrops and extra info! Remember to set the source
            to {{config('other.source')}} if you want to use it directly without redownloading! MAKE SURE TO FILL IN ALL FIELDS!
          </div>
        </div>
      </div>

      <div class="heading">Upload a Torrent</div>
      <div class="upload col-md-12">
        {{ Form::open(['route' => 'upload', 'files' => true, 'class' => 'upload-form']) }}
        <div class="flex mbox mbox--small-bottom">
          <label for="torrent" class="label badge badge col col--medium mbox mbox--mini-right">Torrent File</label>
          <input class="upload-form-file" type="file" accept=".torrent" name="torrent" id="torrent"
                 onchange="updateTorrentName()" required>
        </div>

        {{--<div class="form-group">
          <label for="nfo">NFO File (Optional)</label>
          <input class="upload-form-file" type="file" accept=".nfo" name="nfo">
      </div>--}}

        <div class="flex mbox mbox--small-bottom">
          <label for="name" class="badge col col--medium mbox mbox--mini-right">Title</label>
          <input type="text" name="name" id="title" class="flex__expanded" required/>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="name" class="badge col col--medium mbox mbox--mini-right">Tags</label>
          <input type="text" name="tags" class="flex__expanded" required>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="name" class="badge col col--medium mbox mbox--mini-right">IMDB ID <b>(Required)</b></label>
          <input type="number" name="imdb" value="0" class="flex__expanded" required>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="name" class="badge col col--medium mbox mbox--mini-right">TMDB ID <b>(Required)</b></label>
          <input type="number" name="tmdb" value="0" class="flex__expanded" required>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="name" class="badge col col--medium mbox mbox--mini-right">TVDB ID <b>(Optional)</b></label>
          <input type="number" name="tvdb" value="0" class="flex__expanded" required>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="name" class="badge col col--medium mbox mbox--mini-right">MAL ID <b>(Optional)</b></label>
          <input type="number" name="mal" value="0" class="flex__expanded" required>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="category_id" class="badge col col--medium mbox mbox--mini-right">Category</label>
          <select name="category_id" class="flex__expanded">
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <label for="type" class="badge col col--medium mbox mbox--mini-right">Type</label>
          <select name="type_id" class="flex__expanded">
            @foreach($types as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <div class="heading">Description</div>
          <textarea class="textarea textarea--vertical" id="upload-form-description" name="description" cols="30" rows="10" class="form-control"></textarea>
        </div>

        <div class="parser"></div>
        <center id="upload-buttons">
          <button type="submit" name="preview" value="true" id="preview" class="btn">Preview</button>
          <button id="add" class="btn">Add MediaInfo Parser</button>
          <button type="submit" name="post" value="true" id="post" class="btn">Upload</button>
          <label class="v-checkbox">
            <input type="checkbox" name="anonymous"/>
            <span></span>
            Anonymous upload
          </label>
        </center>
        <br>
        {{ Form::close() }}
      </div>
    </div>
  @endif
@endsection

@section('javascripts')
  <script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
  <script>
      $(document).ready(function () {
          var wbbOpt = {}
          $("#upload-form-description").wysibb(wbbOpt);
      });
  </script>

  <script type="text/javascript">
      $('#add').on('click', function (e) {
          if ($('#mediainfo').length != 0) {
              return;
          }
          e.preventDefault();
          var optionHTML = '<div class="flex flex--fluid"><div class="heading">Mediainfo</div><textarea rows="2" class="textarea" name="mediainfo" cols="50" id="mediainfo" placeholder="Paste MediaInfo Dump Here"></textarea></div>';
          $('.parser').append(optionHTML);
      });
  </script>
  <script>
      function updateTorrentName() {
          let name = document.querySelector("#title");
          let torrent = document.querySelector("#torrent");
          let fileEndings = [".mkv.torrent", ".torrent"];
          let allowed = ["1.0", "2.0", "5.1", "7.1", "H.264"];
          let separators = ["-", " ", "."];
          if (name !== null && torrent !== null) {
              let value = torrent.value.split('\\').pop().split('/').pop();
              fileEndings.forEach(function (e) {
                  if (value.endsWith(e)) {
                      value = value.substr(0, value.length - e.length);
                  }
              });
              value = value.replace(/\./g, " ");
              allowed.forEach(function (a) {
                  search = a.replace(/\./g, " ");
                  let replaceIndexes = [];
                  let pos = value.indexOf(search);
                  while (pos !== -1) {
                      let start = pos > 0 ? value[pos - 1] : " ";
                      let end = pos + search.length < value.length ? value[pos + search.length] : " ";
                      if (separators.includes(start) && separators.includes(end)) {
                          replaceIndexes.push(pos);
                      }
                      pos = value.indexOf(search, pos + search.length);
                  }
                  newValue = "";
                  ignore = 0;
                  for (let i = 0; i < value.length; ++i) {
                      if (ignore > 0) {
                          --ignore;
                      } else if (replaceIndexes.length > 0 && replaceIndexes[0] == i) {
                          replaceIndexes.shift();
                          newValue += a;
                          ignore = a.length - 1;
                      } else {
                          newValue += value[i];
                      }
                  }
                  value = newValue;
              })
              name.value = value;
          }
      }
  </script>
@endsection
