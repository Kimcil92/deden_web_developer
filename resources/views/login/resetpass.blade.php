@extends('login.part.area')

@section('section')

    <h1>Reset Password</h1>
    <div class="main-agileinfo">
        <div class="agileits-top">
            <form action="#" method="post">
                <label>
                    <input class="text email" type="email" name="email" placeholder="Email" required="">
                </label>

                <input type="submit" value="SIGN IN">
            </form>
            <p>Back To Login? <a href="/login"> Login</a></p>
        </div>
    </div>

@endsection
