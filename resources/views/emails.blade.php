<!DOCTYPE html>
<html>
<head>
    <title>Send Bulk Emails</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Send Bulk Emails</h2>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('send.bulk.emails') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Send Bulk Emails</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
