<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login Module</title>
</head>
<body>
    <div class="container-fluid pt-md-3 pb-md-3 mt-3">
        <div class="border border-secondary rounded rounded-4 border-4 ms-6 me-6">
            <div class="container text-center pt-3 pb-3">
                <h1>Login</h1>
            </div>
            <form action="login.php" method="post">
                <div class="pt-3 pb-3 ms-5 me-5">
                    <div class="mt-3 mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="inputPassword1" class="form-label border-secondary">Password</label>
                        <input type="password" name="password" class="form-control" aria-describedby="passwordHelpBlock">
                        <div id="passwordHelpBlock" class="form-text">
                        </div>
                    </div>
                    
                    <div class="mt-3 mb-3">
                        <input type="submit" class="btn btn-outline-secondary mb-3" value="Accedi">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
