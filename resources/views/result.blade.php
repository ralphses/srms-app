<x-app-layout>

    <style>
        @media print {
            /* Hide header, breadcrumb, and buttons */
            header, .bg-primary-dark, .breadcrumb, .btn, .gap-2, nav {
                display: none !important;
            }

            /* Ensure only the result table prints */
            body * {
                visibility: hidden;
            }

            #printable-result-area, #printable-result-area * {
                visibility: visible;
            }

            #printable-result-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome Yusuf Agabi</span>
                        </h1>
                    </div>
                    <div class="col-md py-2 d-md-flex align-items-md-center justify-content-md-end text-center gap-2">
                        <a href="{{ route('home') }}" class="btn btn-alt-primary">
                            <i class="fa fa-arrow-left opacity-50 me-1"></i> Back to Home
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fa fa-print me-1"></i> Print Result
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Header -->

        <!-- Page Content -->
        <div class="bg-body-extra-light">
            <!-- Breadcrumb -->
            <div class="content">
                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-0">
                    <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                    <span class="breadcrumb-item active">Result</span>
                </nav>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="row">
                    <!-- Printable Area -->
                    <div id="printable-result-area">
                        <!-- Semester Result Table -->
                        <div class="col-xl-12">
                            <div class="block block-rounded block-bordered">
                                <div class="block-header">
                                    <h3 class="block-title text-uppercase">Semester Result (2023/2024 - First Semester)</h3>
                                </div>
                                <div class="block-content block-content-full">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th>S/N</th>
                                            <th>Course Name</th>
                                            <th>Course Code</th>
                                            <th>Credit Unit</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <th>Remark</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Computer Fundamentals</td>
                                            <td>CSC101</td>
                                            <td>3</td>
                                            <td>89</td>
                                            <td>A</td>
                                            <td>Distinction</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Calculus I</td>
                                            <td>MTH101</td>
                                            <td>3</td>
                                            <td>75</td>
                                            <td>B</td>
                                            <td>Very Good</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Physics for Scientists</td>
                                            <td>PHY101</td>
                                            <td>3</td>
                                            <td>65</td>
                                            <td>C</td>
                                            <td>Credit</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Communication in English</td>
                                            <td>ENG101</td>
                                            <td>2</td>
                                            <td>54</td>
                                            <td>D</td>
                                            <td>Good</td>
                                        </tr>

                                        <!-- Spacer Row -->
                                        <tr><td colspan="7" class="bg-white" style="height: 30px;"></td></tr>

                                        <!-- Result Analysis Rows -->
                                        <thead class="table-dark">
                                        <tr>
                                            <th colspan="7" class="text-center text-uppercase">Result Analysis</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td colspan="2"><strong>Current Semester</strong></td>
                                            <td><strong>CUR:</strong> 4</td>
                                            <td><strong>CUE:</strong> 11</td>
                                            <td><strong>WGP:</strong> 46.0</td>
                                            <td colspan="2"><strong>GPA:</strong> 4.18</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Previous Semester</strong></td>
                                            <td><strong>TCUR:</strong> 5</td>
                                            <td><strong>TCUE:</strong> 14</td>
                                            <td><strong>TWGP:</strong> 56.5</td>
                                            <td colspan="2"><strong>CGPA:</strong> 4.04</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Cumulative</strong></td>
                                            <td><strong>TCUR:</strong> 9</td>
                                            <td><strong>TCUE:</strong> 25</td>
                                            <td><strong>TWGP:</strong> 102.5</td>
                                            <td colspan="2"><strong>CGPA:</strong> 4.10</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END Semester Result Table -->
                    </div>
                    <!-- END Printable Area -->
                </div>
            </div>
            <!-- END Content -->
        </div>
        <!-- END Page Content -->
    </main>

</x-app-layout>
