<x-app-layout>
    <main id="main-container">
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">Update Password</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-body-extra-light">
            <div class="content">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">Change Your Password</h3>
                    </div>
                    <div class="block-content">
                        <form action="{{ route('password.update.submit', ['role' => auth()->user()->role]) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-app-layout>
