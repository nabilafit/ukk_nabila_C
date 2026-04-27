<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="text-center mb-3">Login</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <a href="/register" class="d-block text-center mt-2">Belum punya akun? Register</a>
    </div>
</div>

</body>
</html>