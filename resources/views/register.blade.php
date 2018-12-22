<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Register</title>

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
</head>

<body>
    <form action="/token" id="registerForm">
        Email: <input type="email" required name="email" id="email">
        Password: <input type="password" required name="password" id="password">
        <button type="submit">Submit</button>
    </form>

    <script>
        $("#registerForm").submit(function (e) {
            e.preventDefault();

            var email = $("#email").val();
            var password = $("#password").val();
            $.post( "/register", { email, password, _token: "{{csrf_token()}}" })
                .done(function( data ) {
                    console.log(data);
                });
        })
    </script>
</body>
</html>


