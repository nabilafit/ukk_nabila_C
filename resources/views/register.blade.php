<!DOCTYPE HTML>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="text-center mb-3">Register</h4>

        <form method="POST" action="/register">
            @csrf

            <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
            <input type="text" name="nis" class="form-control mb-2" placeholder="NIS" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

            <button class="btn btn-success w-100">Register</button>
        </form>

        <a href="/login" class="text-center mt-2">Sudah punya akun? Login</a>
   </div>
</div>

</body>
</html>
