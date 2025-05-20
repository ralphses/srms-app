<x-app-layout>

    <style>
        @media print {
            header, .bg-primary-dark, .breadcrumb, .btn, .gap-2, nav {
                display: none !important;
            }

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

    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome {{ auth()->user()->name }}</span>
                        </h1>
                    </div>
                    <div class="col-md py-2 d-md-flex align-items-md-center justify-content-md-end text-center gap-2">
                        <a href="{{ route('dashboard', ['role' => auth()->user()->role]) }}" class="btn btn-alt-primary">
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
                    <a class="breadcrumb-item" href="{{ route('dashboard', ['role' => auth()->user()->role]) }}">Home</a>
                    <span class="breadcrumb-item active">Result</span>
                </nav>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="row">
                    <!-- Printable Area -->
                    <div id="printable-result-area" class="col-xl-12">

                        @if($results && $results->studentResult)
                            <!-- Student Details -->
                            <div class="block block-rounded block-bordered mb-3">
                                <div class="block-header">
                                    <h3 class="block-title text-uppercase">Student Information</h3>
                                </div>
                                <div class="block-content">
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Name:</strong> {{ $results->studentResult->studentName }}</div>
                                        <div class="col-md-4"><strong>Matric No:</strong> {{ $results->studentResult->studentMatric }}</div>
                                        <div class="col-md-4"><strong>Department:</strong> {{ $results->studentResult->department }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Level:</strong> {{ $results->studentResult->level }}</div>
                                        <div class="col-md-4"><strong>Programme Type:</strong> {{ ucfirst($results->studentResult->programType) }}</div>
                                        <div class="col-md-4"><strong>Session:</strong> 2023/2024</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><strong>Start Session:</strong> {{ $results->studentResult->startSession }}</div>
                                        <div class="col-md-4"><strong>End Session:</strong> {{ $results->studentResult->endSession }}</div>
                                        <div class="col-md-4"><strong>Is Current Session:</strong> {{ $results->studentResult->isSession ? "Yes" : "No" }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Semester Result Table -->
                            @foreach($results->studentResult->semesterResults as $semesterResult)
                                <div class="block block-rounded block-bordered">
                                    <div class="block-header">
                                        <h3 class="block-title text-uppercase">{{ ucfirst($semesterResult->semester) }} Semester (2023/2024)</h3>
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
                                            @foreach($semesterResult->semesterResultInputDtos as $result)
                                                <tr>
                                                    <td>{{ ++$loop->index }}</td>
                                                    <td>{{ $result->courseName }}</td>
                                                    <td>{{ $result->courseCode }}</td>
                                                    <td>{{ $result->courseUnit }}</td>
                                                    <td>{{ $result->score }}</td>
                                                    <td>{{ $result->grade }}</td>
                                                    <td>{{ $result->remark }}</td>
                                                </tr>
                                            @endforeach

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
                                                <td><strong>CUR:</strong> {{ $semesterResult->academicSummary['current']['CUR'] }}</td>
                                                <td><strong>CUE:</strong> {{ $semesterResult->academicSummary['current']['CUE'] }}</td>
                                                <td><strong>WGP:</strong> {{ $semesterResult->academicSummary['current']['WGP'] }}</td>
                                                <td colspan="2"><strong>GPA:</strong> {{ number_format($semesterResult->academicSummary['current']['GPA'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><strong>Previous Semester</strong></td>
                                                <td><strong>CUR:</strong> {{ $semesterResult->academicSummary['previous']['TCUR'] }}</td>
                                                <td><strong>CUE:</strong> {{ $semesterResult->academicSummary['previous']['TCUE'] }}</td>
                                                <td><strong>WGP:</strong> {{ $semesterResult->academicSummary['previous']['TWGP'] }}</td>
                                                <td colspan="2"><strong>GPA:</strong> {{ number_format($semesterResult->academicSummary['previous']['CGPA'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><strong>Cumulative</strong></td>
                                                <td><strong>CUR:</strong> {{ $semesterResult->academicSummary['cumulative']['TCUR'] }}</td>
                                                <td><strong>CUE:</strong> {{ $semesterResult->academicSummary['cumulative']['TCUE'] }}</td>
                                                <td><strong>WGP:</strong> {{ $semesterResult->academicSummary['cumulative']['TWGP'] }}</td>
                                                <td colspan="2"><strong>GPA:</strong> {{ number_format($semesterResult->academicSummary['cumulative']['CGPA'], 2) }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                            <!-- END Semester Result Table -->

                        @else
                            <!-- No Results Found Message -->
                            <div class="block block-rounded block-bordered text-center py-5">
                                <h4 class="text-muted">No matching record found</h4>
                            </div>
                        @endif

                    </div>
                    <!-- END Printable Area -->
                </div>
            </div>
            <!-- END Content -->
        </div>
        <!-- END Page Content -->
    </main>

</x-app-layout>
