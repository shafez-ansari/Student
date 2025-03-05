@extends('home.home-master')

@section('content')
<style>
    .form-container {
      
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.35);
    }

    .form-header {
        background: #333;
        color: white;
        padding: 10px;
        border-radius: 8px 8px 0 0;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .form-section {
        display : grid;
        margin-bottom: 15px;
        Width : 100%;
    }

    .form-section label {
        margin-top: 20px;
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .form-control {
        text-align: center;
        height: auto;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }
    .dropdown-toggle::after {
    display: none;
    margin-left: .255em;
    vertical-align: .255em;
    content: "";
    border-top: .3em solid;
    border-right: .3em solid transparent;
    border-bottom: 0;
    border-left: .3em solid transparent;
}
    .form-submit {
        text-align: center;
    }

    .btn-primary {
        background: #ff0000;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary:hover {
        background: #ff0000;
    }
    .btn {
        height: 40px;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 15px;
        }

        .btn-primary {
            width: 100%;
        }
    }
    
</style>

<form action="" id="entityFormId">
    @csrf
    <input type="hidden" value="{{ $entityName }}" id="hdnEntityName">
    <input type="hidden" value="{{ $yes }}" id="hdnYes">
    <input type="hidden" value="{{ $no }}" id="hdnNo">
    <input type="hidden" value="{{ $school }}" id="hdnSchool">
    <input type="hidden" value="{{ $course }}" id="hdnCourse">

    <div class="form-container">
        <div class="form-header">Student Details</div>

        @if($yes == 1)
            @if($entityName == "AAFT Noida" || $entityName == "AAFT University")
                <div class="form-section">
                    <label for="qualId">Your last completed academic qualification</label>
                    <select name="qualId" id="qualId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($academicQual as $qual)
                            <option value="{{$qual->qualification_id}}">{{$qual->qualification_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="qualError"></span>
                </div>

                <div class="form-section">
                    <label for="jobProfileId">I want to work for the job profile</label>
                    <select name="jobProfileId" id="jobProfileId" class="form-control" multiple="multiple">
                        @foreach($jobProfile as $job)
                            <option value="{{$job->profile_name}}">{{$job->profile_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobProfileError"></span>
                </div>

                <div class="form-section">
                    <label for="jobTypeId">What job type are you interested in?</label>
                    <select name="jobTypeId" id="jobTypeId" class="form-control" multiple="multiple">
                        @foreach($jobType as $job)
                            <option value="{{$job->job_type_name}}">{{$job->job_type_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobTypeError"></span>
                </div>

                <div class="form-section">
                    <label for="jobLocationId">Preferred Location of Job/Employment</label>
                    <select name="jobLocationId" id="jobLocationId" class="form-control" multiple="multiple">
                        @foreach($empLocation as $loc)
                            <option value="{{$loc->emp_loc_name}}">{{$loc->emp_loc_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobLocationError"></span>
                </div>

                <div class="form-section">
                    <label for="workExp">Relevant Work Experience (In Years)</label>
                    <input type="number" name="workExp" id="workExp" class="form-control">
                    <span class="text-danger" id="workExpError"></span>
                </div>
            @elseif($entityName == "AAFT Online")
                <div class="form-section">
                    <label for="stateId">State</label>
                    <select name="stateId" id="stateId" class="form-control" onchange="getCity()">
                        <option value="">--Select--</option>
                        @foreach($state as $state)
                            <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="stateIdError"></span>
                </div>

                <div class="form-section">
                    <label for="cityId">City</label>
                    <select name="cityId" id="cityId" class="form-control">
                        <option value="">--Select--</option>
                    </select>
                    <span class="text-danger" id="cityIdError"></span>
                </div>

                <div class="form-section">
                    <label for="qualId">Highest Academic Qualification</label>
                    <select name="qualId" id="qualId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($academicQual as $qual)
                            <option value="{{$qual->qualification_id}}">{{$qual->qualification_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="qualError"></span>
                </div>

                <div class="form-section">
                    <label for="empStatusId">Current Employment Status</label>
                    <select name="empStatusId" id="empStatusId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($empStatus as $emp)
                            <option value="{{$emp->emp_status_id}}">{{$qual->emp_status_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="empStatusError"></span>
                </div>

                <div class="form-section">
                    <label for="careerSupportId">What motivates your need for career support?</label>
                    <select name="careerSupportId" id="careerSupportId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($careerSupport as $career)
                            <option value="{{$career->career_id}}">{{$career->career_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="careerSupportError"></span>
                </div>

                <div class="form-section">
                    <label for="techSkillId">Technical Skills (Software Specific Keywords as per your domain)</label>
                    <input type="text" name="techSkillId" id="techSkillId" class="form-control" />            
                    <span class="text-danger" id="techSkillError"></span>
                </div>

                <div class="form-section">
                    <label for="jobProfileId">Preferred Job Roles</label>
                    <select name="jobProfileId" id="jobProfileId" class="form-control" multiple="multiple">
                        @foreach($jobProfile as $job)
                            <option value="{{ $job->jobName }}">{{ $job->jobName }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobProfileError"></span>
                </div>

                <div class="form-section">
                    <label for="jobRolesId">Are any internship or volunteer experiences relevant to your preferred job roles?</label>
                    <select name="jobRolesId" id="jobRolesId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($jobRoles as $jobRole)
                            <option value="{{$jobRole->job_role_id}}">{{$jobRole->job_role_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobRoleError"></span>
                </div>

                <div class="form-section">
                    <label for="jobRelocateId">Willingness to Relocate?</label>
                    <select name="jobRelocateId" id="jobRelocateId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($jobRelocate as $jobRelocate)
                            <option value="{{$jobRelocate->job_relocate_id}}">{{$jobRelocate->job_relocate_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobRelocateError"></span>
                </div>
                
                <div class="form-section">
                    <label for="jobLocationId">Preferred Job Location?</label>
                    <select name="jobLocationId" id="jobLocationId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($state as $state)
                            <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobLocationError"></span>
                </div>

                <div class="form-section">
                    <label for="workTypeId">Preferred Work Type</label>
                    <select name="workTypeId" id="workTypeId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($workType as $work)
                            <option value="{{$work->work_type_id}}">{{$work->work_type_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="workTypeError"></span>
                </div>

                <!-- Additional fields as per requirements -->
            @endif
        @else
            <div class="form-section">
                <label for="notPlacementId">Reason for not looking for placement</label>
                <select name="notPlacementId" id="notPlacementId" class="form-control" onchange="notPlacement()">
                    <option value="">--Select--</option>
                    @foreach($reasonNotPlacing as $reason)
                        <option value="{{$reason->not_placement_id}}">{{$reason->not_placement_name}}</option>
                    @endforeach
                </select>
                <span class="text-danger" id="notPlacementError"></span>
            </div>

            <div class="form-section" id="intervalDiv" style="display:none">
                <label for="intervalId">Duration</label>
                <input type="text" id="intervalId" name="intervalId" class="form-control">
                <span class="text-danger" id="intervalError"></span>
            </div>
        @endif

        <div class="form-submit">
            <div id="submitQuestionId" class="btn btn-primary" onclick="submitQuestionarie()">Submit</div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#jobProfileId, #jobTypeId, #jobLocationId').multiselect({
            numberDisplayed: 1,
            enableFiltering: true
        });
    });

    function submitQuestionarie() {
        // Add form submission logic here
          
        var entityId = $("#hdnEntityName").val();
        var yes = $("#hdnYes").val();
        var no = $("#hdnNo").val();
        var school = $("#hdnSchool").val();
        var course = $("#hdnCourse").val();
        if(yes == 1) {
            if(entityId == "AAFT Noida" || entityId == "AAFT University") {
                
                var qualId = $("#qualId").val();
                var jobProfileId = $("#jobProfileId").val();
                var jobTypeId = $("#jobTypeId").val();
                var jobLocationId = $("#jobLocationId").val();
                var workExp = $("#workExp").val();
                if(qualId == "") {
                    $("#qualError").text("Please select your last completed academic qualification");
                    
                } else {
                    $("#qualError").text("");
                }

                if(jobProfileId == null) {
                    $("#jobProfileError").text("Please select job profile");
                    
                }
                else if(jobProfileId.length >= 3) {
                    $("#jobProfileError").text("Please select maximum 2 job profile");
                    
                }
                else {
                    $("#jobProfileError").text("");
                }

                if(jobTypeId == null) {
                    $("#jobTypeError").text("Please select job type");
                    
                }
                else if(jobTypeId.length >= 3) {
                    $("#jobTypeError").text("Please select maximum 2 job type");
                    
                } 
                else {
                    $("#jobTypeError").text("");
                }

                if(jobLocationId == null) {
                    $("#jobLocationError").text("Please select job location");
                    
                }
                else if(jobLocationId.length >= 3) {
                    $("#jobLocationError").text("Please select maximum 2 job location");                    
                }
                else {
                    $("#jobLocationError").text("");
                }

                if(workExp == "") {
                    $("#workExpError").text("Please enter relevant work experience");
                    
                } else {
                    $("#workExpError").text("");
                }

                if($("#qualError").text() == "" && $("#jobProfileError").text() == "" && $("#jobTypeError").text() == "" && $("#jobLocationError").text() == "" && $("#workExpError").text() == "") {
                    // Submit form
                    
                    $.ajax({
                        type: "GET",
                        url: "/submit-questionarie",
                        data: {qualId : qualId, jobProfileId : jobProfileId, jobTypeId : jobTypeId, jobLocationId : jobLocationId, workExp : workExp, entityName : entityId, school : school, course : course, yes : yes, no : no},
                        success: function(response) {
                            if(response) {    
                                let url = `/thankYou/`;                            
                                window.location.href = url;
                            } 
                        }
                    });
                }
                else {
                    return false;
                }

            } 
            else if(entityId == "AAFT Online") {
                var stateId = $("#stateId").val();
                var cityId = $("#cityId").val();
                var qualId = $("#qualId").val();
                var empStatusId = $("#empStatusId").val();
                var careerSupportId = $("#careerSupportId").val();
                var techSkillId = $("#techSkillId").val();
                var jobProfileId = $("#jobProfileId").val();
                var jobRolesId = $("#jobRolesId").val();
                var jobRelocateId = $("#jobRelocateId").val();
                var jobLocationId = $("#jobLocationId").val();
                var workTypeId = $("#workTypeId").val();
                
                if(stateId == "") {
                    $("#stateIdError").text("Please select state");
                    
                } else {
                    $("#stateIdError").text("");
                }

                if(cityId == "") {
                    $("#cityIdError").text("Please select city");
                    
                } else {
                    $("#cityIdError").text("");
                }

                if(qualId == "") {
                    $("#qualError").text("Please select highest academic qualification");
                    
                } else {
                    $("#qualError").text("");
                }

                if(empStatusId == "") {
                    $("#empStatusError").text("Please select current employment status");
                    
                } else {
                    $("#empStatusError").text("");
                }

                if(careerSupportId == "") {
                    $("#careerSupportError").text("Please select career support motivation");
                    
                } else {
                    $("#careerSupportError").text("");
                }

                if(techSkillId == "") {
                    $("#techSkillError").text("Please enter technical skills");
                    
                } else {
                    $("#techSkillError").text("");
                }

                if(jobProfileId == null) {
                    $("#jobProfileError").text("Please select preferred job roles");
                    
                } 
                else if(jobProfileId.length >= 3) {
                    $("#jobProfileError").text("Please select maximum 2 job profile");                    
                }
                else {
                    $("#jobProfileError").text("");
                }

                if(jobRolesId == "") {
                    $("#jobRoleError").text("Please select relevant internship or volunteer experiences");
                    
                } else {
                    $("#jobRoleError").text("");
                }

                if(jobRelocateId == "") {
                    $("#jobRelocateError").text("Please select willingness to relocate");
                    
                } else {
                    $("#jobRelocateError").text("");
                }

                if(jobLocationId == "") {
                    $("#jobLocationError").text("Please select preferred job location");
                    
                } else {
                    $("#jobLocationError").text("");
                }

                if(workTypeId == "") {
                    $("#workTypeError").text("Please select preferred work type");
                    
                } else {
                    $("#workTypeError").text("");
                }

                if($("#stateIdError").text() == "" && $("#cityIdError").text() == "" && $("#qualError").text() == "" && $("#empStatusError").text() == "" && $("#careerSupportError").text() == "" && $("#techSkillError").text() == "" && $("#jobProfileError").text() == "" && $("#jobRoleError").text() == "" && $("#jobRelocateError").text() == "" && $("#jobLocationError").text() == "" && $("#workTypeError").text() == "") {
                    // Submit form                    
                    $.ajax({
                        type: "GET",
                        url: "{{  url('submit-questionarie') }}",
                        data: { stateId : stateId, cityId:cityId, qualId : qualId, empStatusId : empStatusId, careerSupportId : careerSupportId, techSkillId : techSkillId, jobProfileId : jobProfileId, jobRolesId : jobRolesId, jobLocationId : jobLocationId, jobRelocateId : jobRelocateId, workTypeId : workTypeId, entityId : entityId, school : school, course : course, yes : yes, no : no },
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if(response) {                                
                                let url = `/thankYou/`;                            
                                window.location.href = url;
                            } 
                        }
                    });
                }
                else {
                    return false;
                }
            }
        }
        else {
            var notPlacementId = $("#notPlacementId").val();
            var intervalId = $("#intervalId").val();
            if(notPlacementId == "") {
                $("#notPlacementError").text("Please select reason for not looking for placement");
                
            } else {
                $("#notPlacementError").text("");
            }

            if(notPlacementId == 6) {
                if(intervalId == "") {
                    $("#intervalError").text("Please enter duration");
                    
                } else {
                    $("#intervalError").text("");
                }
            }
            
            if($("#notPlacementError").text() == "" && $("#intervalError").text() == "") {
                // Submit form
                $.ajax({
                    type: "GET",
                    url: "/submit-questionarie",
                    data: { notPlacementId : notPlacementId, intervalId : intervalId, entityId : entityId, school : school, course : course, yes : yes, no : no },
                    
                    success: function(response) {
                        if(response) {                                
                            let url = `/thankYou/`;                            
                            window.location.href = url;
                        } 
                    }
                });
            }
            else {
                return false;
            }
        }            
    }

    function notPlacement() {
        if ($("#notPlacementId option:selected").text() == "Seeking after an Interval") {
            $("#intervalDiv").show();
        } else {
            $("#intervalDiv").hide();
        }
    }

    function getCity() {
        var stateId = $("#stateId").val();
        
        $.ajax({
            type: "GET",
            url: "/get-city",
            data: { stateId: stateId },
            success: function(response) {
                if(response) {
                    var cityId = $("#cityId").empty();
                    cityId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.cityList.length;i++){
                        var city_item_el = '<option value="' + data.cityList[i]['city_id']+'">'+ data.cityList[i]['city_name']+'</option>';
                        cityId.append(course_item_el);
                    }
                }
            }
        });
    }

</script>

@endsection
