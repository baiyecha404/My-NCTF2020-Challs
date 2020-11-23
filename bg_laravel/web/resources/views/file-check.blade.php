<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <title>File Check</title>
    <style>
        .container {
            max-width: 500px;
        }
        dl, ol, ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <form action="{{route('fileCheck')}}" method="post" >
        <h3 class="text-center mb-5">Check Your File for TeacherMa</h3>
        @csrf
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <input type="text" name="filename" class="form-control" placeholder="filename">
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
            Check The File
        </button>
    </form>
</div>

</body>
</html>
