<x-app-layout>
    <main id="main-container">
        <!-- Page Content -->
        <div class="bg-gd-dusk">
            <div class="hero-static content content-full bg-body-extra-light">
                <!-- Header -->
                <div class="py-4 px-1 text-center mb-4">
                    <h1 class="h3 fw-bold mt-5 mb-2">Welcome</h1>
                    <h2 class="h5 fw-medium text-muted mb-0">Please sign in</h2>
                </div>
                <!-- END Header -->

                <!-- Sign In Form -->
                <div class="row justify-content-center px-1">
                    <div class="col-sm-8 col-md-6 col-xl-4">
                        <!-- jQuery Validation functionality is initialized with .js-validation-signin class in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js -->
                        <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                        <form class="js-validation-signin" action="be_pages_auth_all.html" method="POST">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="login-username" name="login-username" placeholder="Enter your username">
                                <label class="form-label" for="login-username">Username</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="login-password" name="login-password" placeholder="Enter your password">
                                <label class="form-label" for="login-password">Password</label>
                            </div>
                            <div class="row g-sm mb-4">
                                <div class="col-12 mb-2">
                                    <button type="submit" class="btn btn-lg btn-alt-primary w-100 py-3 fw-semibold">
                                        Sign In
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Sign In Form -->
            </div>
        </div>
        <!-- END Page Content -->
    </main>
</x-app-layout>
