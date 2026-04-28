<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-body">

            <form method="POST" action="/items/update/{{ $item->id }}" enctype="multipart/form-data">
                @csrf

                <input type="text" name="name" value="{{ $item->name }}" class="form-control mb-2">

                <input type="number" name="stock" value="{{ $item->stock }}" class="form-control mb-2">

                <input type="file" name="image" class="form-control mb-2">

                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" width="120" class="mb-2">
                @endif

                <button class="btn btn-primary">Update</button>

            </form>

        </div>
    </div>

</div>

</body>
</html>