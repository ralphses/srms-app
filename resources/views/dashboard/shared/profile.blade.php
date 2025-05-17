@php use App\Utils\Utils; @endphp
<x-app-layout>

    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Student Profile</span>
                        </h1>
                    </div>
                    <div class="col-md py-2 d-md-flex align-items-md-center justify-content-md-end text-center">
                        <a href="{{ route('dashboard', ['role' => auth()->user()->role]) }}"
                           class="btn btn-alt-primary">
                            <i class="fa fa-arrow-left opacity-50 me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Header -->

        <!-- Breadcrumb -->
        <div class="bg-body-extra-light">
            <div class="content">
                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-0">
                    <a class="breadcrumb-item" href="#">Home</a>
                    <a class="breadcrumb-item" href="{{ route('dashboard', ['role' => auth()->user()->role]) }}">Dashboard</a>
                    <span class="breadcrumb-item active">Profile</span>
                </nav>
            </div>
        </div>
        <!-- END Breadcrumb -->

        <!-- Page Content -->
        <div class="content">
            <!-- Profile Info Block -->
            @if(auth()->user()->role == Utils::ROLE_STUDENT)
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Personal Information</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Matric Number:</strong> {{ $profile->matric_no ?? 'N/A' }}</p>
                                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Academic Information</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">

                            <div class="col-md-6">
                                <p><strong>Department:</strong> {{ $profile->department->name ?? 'N/A' }}</p>
                                <p><strong>Level:</strong> {{ $profile->current_level ?? 'N/A' }}</p>
                                <p><strong>Program Type:</strong> {{ $profile->program_type ?? 'N/A' }}</p>
                                <p><strong>Session:</strong> {{ $profile->session->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role == Utils::ROLE_ADMIN)
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Personal Information</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Staff ID:</strong> {{ $profile->staff_id ?? 'N/A' }}</p>
                                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role == Utils::ROLE_LECTURER)
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Personal Information</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Staff ID:</strong> {{ $profile->staff_id ?? 'N/A' }}</p>
                                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Academic Information</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">

                            <div class="col-md-6">
                                <p><strong>Department:</strong> {{ $profile->department->name ?? 'N/A' }}</p>
                                <p><strong>Level:</strong> {{ $profile->level ?? 'N/A' }}</p>
                                <p><strong>Program Type:</strong> {{ $profile->program_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- END Page Content -->
    </main>

</x-app-layout>
