<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #343a40;
            color: white;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .btn-primary, .btn-secondary {
            width: 100%;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Register Here</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('registerSave') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Your Name..." required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Your Email..." required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Your Password..." required>
                            </div>

                            <div class="mb-3">
                                <label for="con_pass" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Enter Your Confirm Password..." required>
                            </div>

                            <div class="mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" name="age" class="form-control" placeholder="Enter Your Age..." required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" class="form-control">
                                    <option value="Admin">Admin</option>
                                    <option value="Author">Author</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</button>
                        </form>

                        <!-- Login Link -->
                        <a href="/login" class="btn btn-secondary mt-3"><i class="fas fa-user-plus"></i> Login</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
