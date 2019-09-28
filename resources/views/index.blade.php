<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fun CaG</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/extra.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron" id="home">
  <h1 class="display-3">Fun CaG</h1>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('logo.jpg') }}" alt="">
        </div>
        <div class="col-md-6">
            <div class="row">
                <form action="{{ action('ParseController@load') }}" method="post" enctype="multipart/form-data" >
                    <div class="form-group">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                        <label for="email">Filename:</label>
                        <input type="file" class="form-control-file" id="archive" name="archive">
                    </div>
                    <button type="submit" class="btn btn-primary">Analyze</button>
                </form>            
            </div>
            <div class="row">
                <dl class="row">
                    <dt class="col-sm-12">Function Call Graph</dt>
                    <dt class="col-sm-12">Language Type: C</dt>
                    <dt class="col-sm-12">Created: 26 August 2019</dt>
                </dl>
            </div>
        </div>
    </div>
    <div class="row">
        <ol>
            <li>Click "Choose File" button to select file from your computer</li>
            <li>Click "Analyze" to start anlyzing the file</li>
            <small class="text-muted">*Your file should consist with files that has ".C" or ".h" extensions, otherwise it will be ignored</small>
        </ol>    
    </div>
</div>

</body>
</html>
