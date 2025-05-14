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

                        <!-- Display Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf

                            <div class="form-floating mb-4">
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="Enter your email"
                                       value="{{ old('email') }}" required autofocus>
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Enter your password" required>
                                <label for="password">Password</label>
                            </div>

                            <div class="row g-sm mb-4">
                                <div class="col-12 mb-2">
                                    <button type="submit" class="btn btn-lg btn-alt-primary w-100 py-3 fw-semibold">
                                        Sign In
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- END Login Form -->

                    </div>
                </div>
                <!-- END Sign In Form -->
            </div>
        </div>
        <!-- END Page Content -->
    </main>
</x-app-layout>
