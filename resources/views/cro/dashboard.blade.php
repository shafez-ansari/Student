@extends('cro.cro-master')

@section('cro-content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: auto;
            font-size: 14px; /* Set global font size */
        }

        .btn {
            margin: 0px;
            font-size: 14px; /* Match font size for buttons */
        }

        .form-select, .form-control {
            font-size: 14px; /* Match font size for form elements */
        }

        .table {
            font-size: 14px; /* Match font size for table content */
        }

        @media (max-width: 768px) {
            .btn-spacing {
                margin-bottom: 0.5rem;
            }

            .justify-content-between {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }
        }

        .g-3, .gy-3 {
            display: inherit;
            --bs-gutter-y: 1rem;
        }

        .btn-spacing {
            margin-right: 10px;
        }

        /* Sticky Header */
        .table-responsive {
            max-height: 500px; /* Set height for scrollable table */
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #343a40; /* Matches table-dark */
            color: white;
        }
        .mb-3 {
    padding-top: 15px;
    margin-bottom: 1rem !important;
}

    </style>
        <!-- Form Section -->
        <div class="card mb-3 ">
            <div class="card-header bg-dark text-white">
                View Student Data
            </div>
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-2">
                        <select class="form-select" id="entity" name="entity" aria-label="Select Entity" onchange="getSchoolList()">
                            <option selected value="">Select Entity</option>
                            @foreach($entityList as $entity)
                                <option value="{{ $entity->entity_id }}">{{ $entity->entity_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="school" name="school" aria-label="Select School" onchange="getCourseList()">
                            <option selected value="">Select School</option>
                            <option value="">Select Entity First</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="course" name="course" aria-label="Select Course">
                            <option selected value="">Select Course</option>
                            <option value="">Select School First</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select class="form-select" id="optin" name="optin" aria-label="Opt-In">
                            <option selected value="">Select Opt-In</option>
                            @foreach($optin as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div onclick="submitValues()" class="btn btn-success w-100 me-2">Submit</div>                        
                    </div>
                    <div class="col-md-2 d-flex align-items-right">
                        <div class="btn btn-danger" onclick="downloadExcel()">
                            <i class="bi bi-file-earmark-excel"></i> <!-- Excel Icon -->
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table id="studentTableId" class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Entity</th>
                        <th>Unique ID</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Contact No</th>
                        <th>School</th>
                        <th>Program Code</th>
                        <th>Batch Code</th>
                        <th>Semester</th>
                        <th>Enrollment Date</th>
                        <th>Opt-In</th>
                    </tr>
                </thead>
                <tbody id="studentTableBodyId">
                    @foreach($userList as $user)
                        <tr>
                            <td>{{ $user->entity_name }}</td>
                            <td>{{ $user->unique_id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->school_name }}</td>
                            <td>{{ $user->course_code }}</td>
                            <td>{{ $user->batch_code }}</td>
                            <td>{{ $user->semester_code }}</td>
                            <td>{{ $user->enrollment_date }}</td>
                            <td>{{ $user->OPTIN }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#studentTableId').DataTable();
    });

    function getSchoolList() {
        var entity_id = $('#entity').val();
        $.ajax({
            url: "/get-school",
            type: "GET",
            data: { entity_id: entity_id },
            success: function(data) {
                var schoolId = $("#school").empty();
                schoolId.append('<option selected="selected" value="">Select School</option>');
                if(data) {        
                    for(var i = 0; i < data.schoolList.length;i++){
                        var school_item_el = '<option value="' + data.schoolList[i]['school_id']+'">'+ data.schoolList[i]['school_name']+'</option>';
                        schoolId.append(school_item_el);
                    }
                }
            }
        });
    }

    function getCourseList() {
        var school_id = $('#school').val();
        $.ajax({
            url: "/get-course",
            type: "GET",
            data: { school_id: school_id },
            success: function(data) {
                var courseId = $("#course").empty();
                courseId.append('<option selected="selected" value="">Select Course</option>');
                if(data) {        
                    for(var i = 0; i < data.courseList.length;i++){
                        var course_item_el = '<option value="' + data.courseList[i]['course_id']+'">'+ data.courseList[i]['course_name']+'</option>';
                        courseId.append(course_item_el);
                    }
                }
            }
        });
    }

    function submitValues() {
        
        var entity_id = $('#entity').val();
        var school_id = $('#school').val();
        var course_id = $('#course').val();
        var optin = $('#optin').val();
        $.ajax({
            url: "/view-student-details",
            type: "GET",
            data: { entity_id : entity_id, school_id : school_id, course_id : course_id, optin : optin },
            success: function(data) {
                if(data) {
                    
                    var studentTable = $("#studentTableId").DataTable().fnDestroy();
                    // studentTable.destroy();
                    $("#studentTableId").empty();
                    $("#studentTableId").append('<thead class="table-dark"><tr><th>Entity</th><th>Unique ID</th><th>Name</th><th>Email ID</th><th>Contact No</th><th>School</th><th>Program Code</th><th>Batch Code</th><th>Semester</th><th>Enrollment Date</th><th>Opt-In</th></tr></thead>');
                    $("#studentTableId").append('<tbody id="studentTableBodyId"></tbody>'); 
                    for(var i = 0; i < data.userList.length;i++){ 
                        var user_item_el = '<tr><td>'+ data.userList[i]['entity_name'] +'</td><td>'+ data.userList[i]['unique_id'] +'</td><td>'+ data.userList[i]['full_name'] +
                        '</td><td>'+ data.userList[i]['email'] +'</td><td>'+ data.userList[i]['phone'] +'</td><td>'+ data.userList[i]['school_name'] +'</td><td>'+ data.userList[i]['course_code'] +'</td><td>'+ data.userList[i]['batch_code'] +'</td><td>'+ data.userList[i]['semester_code'] +'</td><td>'+ data.userList[i]['enrollment_date'] +'</td><td>'+ data.userList[i]['OPTIN'] +'</td></tr>';
                        $("#studentTableBodyId").append(user_item_el);
                    }
                    $('#studentTableId').DataTable();                        
                }
            }
        });
    }

    function downloadExcel(){
        var entity_id = $('#entity').val();
        var school_id = $('#school').val();
        var course_id = $('#course').val();
        var optin = $('#optin').val();

        window.location.href = "{{ url('download-student-details') }}" + "/" + entity_id + "/" + school_id + "/" + course_id + "/" + optin;
    }

</script>

@endsection
