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
            background-color: #000000; /* Matches table-dark */
            color: white;
        }
        
    </style>
        <!-- Form Section -->
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                Company Details
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
                        <select class="form-select" id="school" name="school" aria-label="Select School" onclick="checkSchool()" onchange="getCourseList()">
                            <option selected value="">Select School</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="course" name="course" aria-label="Select Course">
                            <option selected value="">Select Course</option>
                            
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
            <table id="companyTableId" class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Company Unique ID</th>
                        <th>Company Name</th>
                        <th>Category</th>
                        <th>Website</th>
                        <th>Entity</th>
                        <th>School</th>
                        <th>Course</th>
                        <th>Program Type</th>
                        <th>HR Unique ID</th>
                        <th>Resource Person</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Industry Sector</th>
                        <th>Location</th>
                        <th>Leadsource</th>
                        <th>Lead Stage</th>
                        <th>Industry Engagement</th>                        
                    </tr>
                </thead>
                <tbody id="companyTableBodyId">
                    @foreach($companyList as $user)
                        <tr>
                            <td>{{ $user->comp_unique_id }}</td>
                            <td>{{ $user->comp_name }}</td>
                            <td>{{ $user->comp_category }}</td>
                            <td>{{ $user->comp_website }}</td>
                            <td>{{ $user->entity_name }}</td>
                            <td>{{ $user->school_name }}</td>
                            <td>{{ $user->course_name }}</td>
                            <td>{{ $user->program_name }}</td>
                            <td>{{ $user->hr_unqiue_id }}</td>
                            <td>{{ $user->resource_person }}</td>
                            <td>{{ $user->designation }}</td>
                            <td>{{ $user->primary_email }}</td>
                            <td>{{ $user->primary_phone }}</td>
                            <td>{{ $user->sector_name }}</td>
                            <td>{{ $user->industry_name }}</td>
                            <td>{{ $user->leadsource }}</td>
                            <td>{{ $user->lead_stage }}</td>
                            <td>{{ $user->industry_engagement }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#companyTableId').DataTable();
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
        if(school_id == "") {
            $.notify("Please select an Entity", "warning");
            return;
        }
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

    function checkSchool() {
        var entity_id = $('#entity').val();
        if(entity_id == "") {
            $.notify("Please select an Entity", "warning");
            return;
        }
    }

    function submitValues() {
        
        var entity_id = $('#entity').val();
        var school_id = $('#school').val();
        var course_id = $('#course').val();
        var optin = $('#optin').val();
        $.ajax({
            url: "/view-company-details",
            type: "GET",
            data: { entity_id : entity_id, school_id : school_id, course_id : course_id },
            success: function(data) {
                if(data) {                    
                    var studentTable = $("#companyTableId").DataTable().fnDestroy();
                    // studentTable.destroy();
                    $("#companyTableId").empty();
                    $("#companyTableId").append('<thead class="table-dark"><tr><th>Company Unique ID</th><th>Company Name</th><th>Category</th><th>Website</th><th>Entity</th><th>School</th><th>Course</th><th>Program Type</th><th>HR Unique ID</th><th>Resource Person</th><th>Designation</th><th>Email</th><th>Phone</th><th>Industry Sector</th><th>Location</th><th>Leadsource</th><th>Lead Stage</th><th>Industry Engagement</th></tr></thead>');
                    $("#companyTableId").append('<tbody id="studentTableBodyId"></tbody>'); 
                    for(var i = 0; i < data.companyList.length;i++){ 
                        var user_item_el = '<tr><td>'+ data.companyList[i]['comp_unique_id'] +'</td><td>'+ data.companyList[i]['comp_name'] +'</td><td>'+ data.companyList[i]['comp_category'] +
                        '</td><td>'+ data.companyList[i]['comp_website'] +'</td><td>'+ data.companyList[i]['entity_name'] +'</td><td>'+ data.companyList[i]['school_name'] +'</td><td>'+ data.companyList[i]['course_name'] +'</td><td>'+ data.companyList[i]['program_name'] +'</td><td>'+ data.companyList[i]['hr_unqiue_id'] +'</td><td>'+ data.companyList[i]['resource_person'] +'</td><td>'+ data.companyList[i]['designation'] +'</td><td>'+ data.companyList[i]['primary_email'] +'</td><td>'+ data.companyList[i]['primary_phone'] +'</td><td>'+ data.companyList[i]['sector_name'] +'</td><td>'+ data.companyList[i]['industry_name'] +'</td><td>'+ data.companyList[i]['leadsource'] +'</td><td>'+ data.companyList[i]['lead_stage'] +'</td><td>'+ data.companyList[i]['industry_engagement'] +'</td></tr>';
                        $("#companyTableId").append(user_item_el);
                    }
                    $('#companyTableId').DataTable();                        
                }
            }
        });
    }

    function downloadExcel(){
        var entity_id = $('#entity').val();
        var school_id = $('#school').val();
        var course_id = $('#course').val();

        window.location.href = "{{ url('download-company-details') }}" + "/" + entity_id + "/" + school_id + "/" + course_id;
    }

</script>

@endsection
