@extends('cro.cro-master')

@section('cro-content')

<style>
        /* General Reset */
 
        nav {
            justify-content: space-around;
            background-color: #f5f5f5;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        nav button {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        nav button:hover {
            background-color: #ff0000;
        }
        .main-content {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.35);
        }
        .heading-section {
            background-color: #000;
            color: #fff;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: left;
            margin-bottom: 20px;
        }
        .form-table-wrapper {
            display: grid;
            align-items: flex-start;
            gap: 20px;
        }
        .form-section {
            flex: 1;
        }
        .form-section input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-section button {
            margin-top: 10px;
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 0px;
        }
        .form-section button:hover {
            background-color: #ff0000;
        }
        table {
            flex: 2;
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .add-record {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #ff0000;
            font-weight: bold;
        }
        .add-record:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            header, nav, .main-content {
                padding: 10px;
            }
            .form-table-wrapper {
                flex-direction: column;
            }
            .form-section input {
                width: 100%;
            }
            nav {
                flex-wrap: wrap;
            }
        }
    </style>
    
    <main class="main-content">
        <div class="heading-section">Company</div>
        <div class="form-table-wrapper">
            <div class="form-section">
                <div class="col-md-12" style="margin-bottom: 10px;">
                    <input type="text" id="compSearch" placeholder="Enter new company name">                                        
                        <button type="button" id="btnShow" style="max-width: 25%; margin-left:10px" onclick="showDetails('');">Show Details</button>
                </div>
                <span class="text-danger" id="compSearchValId" style="margin-left:15px"></span>
                <br>
                <a id="createCompanyId" style="display:none; margin-left:15px" onclick="newCompany();" class="add-record">Add New Company</a>
            </div>
            <hr>
            <table id="compLeadDetailId" style="display:none;">
                <thead>
                    <tr>
                        <th>Resource Person</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Entity</th>
                        <th>School</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody id="compLeadBodyId">
                    
                </tbody>
            </table>
            <form id="companyFormId" style="display:none;">
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="">Company Name</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="companyNameId" name="companyName" class="form-control">
                        <span class="text-danger" id="companyNameValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="">Company Category</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="companyCategory" name="companyCategory" class="form-control">
                        <span class="text-danger" id="companyCatValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="">Company Webste</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="companyWebsite" name="companyWebsite" class="form-control">
                        <span class="text-danger" id="companyWebValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="">Company Month</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="companyMonth" name="companyMonth" class="form-control">
                        <span class="text-danger" id="companyMonthValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="">Company Year</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="companyYear" name="companyYear" class="form-control">
                        <span class="text-danger" id="companyYearValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary" >Create</button>
                    </div>
                </div>
            </form>
            <form id="companyLeadFormId" style="display:none;">
                <div class="row form-group">
                    <input type="hidden" id="hdnCompanyId" name="hdnCompanyId" class="form-control">
                    <div class="col-md-3">
                        <label for="entityId">Entity</label>
                    </div>
                    <div class="col-md-6">
                        <select name="entityId" id="entityId" class="form-control" onchange="getSchool()">
                            <option value="">Select Entity</option>
                            @foreach($entityList as $entity)
                                <option value="{{ $entity->entity_id }}">{{ $entity->entity_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="entityValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="schoolId">School</label>
                    </div>
                    <div class="col-md-6">
                        <select name="schoolId" id="schoolId" class="form-control" onchange="getCourse()">
                            <option value="">Select School</option>
                        </select>
                        <span class="text-danger" id="schoolValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="courseId">Course</label>
                    </div>
                    <div class="col-md-6">
                        <select name="courseId" id="courseId" class="form-control">
                            <option value="">Select Course</option>
                        </select>
                        <span class="text-danger" id="courseValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="programTypeId">Program Type</label>
                    </div>
                    <div class="col-md-6">
                        <select name="programTypeId" id="programTypeId" class="form-control">
                            <option value="">Select Program Type</option>
                            @foreach($programList as $programType)
                                <option value="{{ $programType->program_id }}">{{ $programType->program_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="programTypeValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="resourcePersonId">Resource Person</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="resourcePersonId" name="resourcePersonId" class="form-control">
                        <span class="text-danger" id="resourcePersonValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="designationId">Designation</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="designationId" name="designationId" class="form-control">
                        <span class="text-danger" id="designationValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="emailId">Email</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="emailId" name="emailId" class="form-control">
                        <span class="text-danger" id="emailValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="phoneId">Phone Number</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" id="phoneId" name="phoneId" class="form-control" maxlength="10">
                        <span class="text-danger" id="phoneValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="industrySectorId">Industry Sector</label>
                    </div>
                    <div class="col-md-6">
                        <select name="industrySectorId" id="industrySectorId" class="form-control">
                            <option value="">Select Industry Sector</option>
                            @foreach($industrySectorList as $industrySector)
                                <option value="{{ $industrySector->industry_sector_id }}">{{ $industrySector->sector_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="industrySectorValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="industryLocationId">Industry Location</label>
                    </div>
                    <div class="col-md-6">
                        <select name="industryLocationId" id="industryLocationId" class="form-control">
                            <option value="">Select Industry Location</option>
                            @foreach($industryLocationList as $industryLocation)
                                <option value="{{ $industryLocation->industry_loc_id }}">{{ $industryLocation->industry_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="industryLocationValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="leadSourceId">Lead Source</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="leadSourceId" name="leadSourceId" class="form-control">
                        <span class="text-danger" id="leadSourceValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="leadStageId">Lead Stage</label>
                    </div>
                    <div class="col-md-6">
                        <select name="leadStageId" id="leadStageId" class="form-control">
                            <option value="">Select Lead Stage</option>
                            <option value="Active">Active</option>
                            <option value="Cold">Cold</option>
                            <option value="Success">Success</option>
                            <option value="Warm">Warm</option>
                        </select>
                        <span class="text-danger" id="leadStageValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label for="industryEngagementId">Industry Engagement</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="industryEngagementId" id="industryEngagementId" class="form-control">
                        <span class="text-danger" id="industryEngagementValId"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary" >Create</button>
                    </div>
                </div>
            </form>
        </div>
        <a style="display:none;" id="newRecordId" class="add-record" onclick="newRecord();">Add New Record</a>
    </main>
       
    <script type="text/javascript">
        $(document).ready(function() {
            $('#compSearch').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/autocomplete-search",
                        type: "GET",
                        data: { query: request.term },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    console.log(ui.item.value); // Handle selection
                }
            });
        });

        function showDetails(name) {
            var compName = name == "" ? $('#compSearch').val() : name;
            if(compName == "") {
                $("#compSearchValId").text('Please enter company name.');
                return false;
            }
            else {
                $("#compSearchValId").text('');
            }
            $("#compLeadDetailId").hide();
            
            $.ajax({
                url: "/get-company-details",
                type: "GET",
                data: { compName: compName },
                success: function(data) {
                    if(data.compLeadList != "Company does not exist") {
                        $("#compSearchValId").text('');
                        var compLeadBodyId = $("#compLeadBodyId").empty();
                        $("#compLeadDetailId").show();
                        $("#createCompanyId").hide();
                        $("#hdnCompanyId").val(data.compId);
                        if(data.compLeadList.length > 0) {
                        
                        for(var i = 0; i < data.compLeadList.length;i++){ 
                            var comp_item_el = '<tr><td>'+ data.compLeadList[i]['resource_person'] +'</td><td>'+ data.compLeadList[i]['designation'] +'</td><td>'+ data.compLeadList[i]['primary_email'] +
                            '</td><td>'+ data.compLeadList[i]['primary_phone'] +'</td><td>'+ data.compLeadList[i]['entity_name'] +'</td><td>'+ data.compLeadList[i]['school_name'] +'</td><td>'+ data.compLeadList[i]['course_name'] +'</td></tr>';
                            compLeadBodyId.append(comp_item_el);
                            
                        }
                        $("#newRecordId").show();
                        $("#companyFormId").hide();
                        $("#companyFormId").hide();
                        }
                        else {
                            compLeadBodyId.append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                            $("#newRecordId").show();
                        }
                    }
                    else {
                        $("#compLeadDetailId").hide();
                        $("#newRecordId").hide();
                        $("#compSearchValId").empty().text(data.compLeadList);
                        $("#companyFormId").hide();
                        $("#companyLeadFormId").hide();
                        $("#createCompanyId").show();
                    }
                }
            });
        }

        function newCompany() {
            $("#companyFormId").show();
            $("#newRecordId").hide();
            $("#compSearch").attr("disabled", "disabled");
            $("#btnShow").attr("disabled", "disabled");
        }

        function newRecord() {
            $("#companyLeadFormId").show();
            $("#compLeadDetailId").hide();
            $("#newRecordId").hide();
            $("#compSearch").attr("disabled", "disabled");
            $("#btnShow").attr("disabled", "disabled");
        }

        $('#companyFormId').on('submit', function(e) {
            var companyNameId = $("#companyNameId").val();
            var companyCategory = $("#companyCategory").val();
            var companyWebsite = $("#companyWebsite").val();
            var companyMonth = $("#companyMonth").val();
            var companyYear = $("#companyYear").val();

            if(companyNameId == "") {
                $("#companyNameValId").text('Please enter company name.');
            }
            else {
                $("#companyNameValId").text('');
            }

            if(companyCategory == "") {
                $("#companyCatValId").text('Please enter company category.');
            }
            else {
                $("#companyCatValId").text('');
            }

            if(companyWebsite == "") {
                $("#companyWebValId").text('Please enter company website.');
            }
            else {
                $("#companyWebValId").text('');
            }

            if(companyMonth == "") {
                $("#companyMonthValId").text('Please enter company month.');
            }
            else {
                $("#companyMonthValId").text('');
            }

            if(companyYear == "") {
                $("#companyYearValId").text('Please enter company year.');
            }
            else {
                $("#companyYearValId").text('');
            }

            if(companyNameId != "" && companyCategory != "" && companyWebsite != "" && companyMonth != "" && companyYear != "") {
                e.preventDefault();
                $.ajax({
                    url: "/create-company",
                    method: "GET",
                    type: "GET",
                    data: { companyName: companyNameId, companyCategory: companyCategory, companyWebsite: companyWebsite, companyMonth: companyMonth, companyYear: companyYear },
                    success: function(response) {
                        alert("Company created successfully.");
                        $("#hdnCompanyId").val(response.mesg);
                        $("#companyFormId").hide();
                        $("#compSearchValId").empty().text('');
                        $("#compSearch").prop("disabled", false);
                        $("#btnShow").prop("disabled", false);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.mesg);
                        $("#newRecordId").hide();
                    }
                });
            }
            else {
                e.preventDefault();
                return false;
            }
            e.preventDefault();
        });

        $("#companyLeadFormId").on('submit', function(e) {
            
            var compId = $("#hdnCompanyId").val();
            var entityValId = $("#entityId").val();
            var schoolValId = $("#schoolId").val();
            var courseValId = $("#courseId").val();
            var programTypeValId = $("#programTypeId").val();
            var resourcePersonValId = $("#resourcePersonId").val();
            var designationValId = $("#designationId").val();
            var emailValId = $("#emailId").val();
            var phoneValId = $("#phoneId").val();
            var industrySectorValId = $("#industrySectorId").val();
            var industryLocationValId = $("#industryLocationId").val();
            var leadSourceValId = $("#leadSourceId").val();
            var leadStageValId = $("#leadStageId").val();
            var industryEngagementValId = $("#industryEngagementId").val();
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if(entityValId == "") {
                $("#entityValId").text('Please select entity.');
            }
            else {
                $("#entityValId").text('');
            }

            if(schoolValId == "") {
                $("#schoolValId").text('Please select school.');
            }
            else {
                $("#schoolValId").text('');
            }

            if(courseValId == "") {
                $("#courseValId").text('Please select course.');
            }
            else {
                $("#courseValId").text('');
            }

            if(programTypeValId == "") {
                $("#programTypeValId").text('Please select program type.');
            }
            else {
                $("#programTypeValId").text('');
            }

            if(resourcePersonValId == "") {
                $("#resourcePersonValId").text('Please enter resource person.');
            }
            else {
                $("#resourcePersonValId").text('');
            }

            if(designationValId == "") {
                $("#designationValId").text('Please enter designation.');
            }
            else {
                $("#designationValId").text('');
            }

            if(emailValId == "") {
                $("#emailValId").text('Please enter email.');
            }
            else if(!emailRegex.test(emailValId)) {
                $("#emailValId").text('Please enter valid email.');
            }
            else {
                $("#emailValId").text('');
            }

            if(phoneValId == "") {
                $("#phoneValId").text('Please enter phone number.');
            }
            else {
                $("#phoneValId").text('');
            }

            if(industrySectorValId == "") {
                $("#industrySectorValId").text('Please select industry sector');
            }
            else {
                $("#industrySectorValId").text('');
            }

            if(industryLocationValId == "") {
                $("#industryLocationValId").text('Please select industry location');
            }
            else {
                $("#industryLocationValId").text('');
            }

            if(leadSourceValId == "") {
                $("#leadSourceValId").text('Please enter lead source');
            }
            else {
                $("#leadSourceValId").text('');
            }

            if(leadStageValId == "") {
                $("#leadStageValId").text('Please select lead stage');
            }
            else {
                $("#leadStageValId").text('');
            }

            if(industryEngagementValId == "") {
                $("#industryEngagementValId").text('Please enter industry engagement');
            }
            else {
                $("#industryEngagementValId").text('');
            }

            if(entityValId != "" && schoolValId != "" && courseValId != "" && programTypeValId != "" && resourcePersonValId != "" && designationValId != "" && emailValId != "" && phoneValId != "" && industrySectorValId != "" && industryLocationValId != "" && leadSourceValId != "" && leadStageValId != "" && industryEngagementValId != "") {
                e.preventDefault();
                $.ajax({
                    url: "/create-company-lead",
                    method: "GET",
                    type: "GET",
                    data: { compId : compId , entityValId: entityValId, schoolValId: schoolValId, courseValId: courseValId, programValId: programTypeValId, resourcePersonValId: resourcePersonValId, designationValId: designationValId, emailValId: emailValId, phoneValId: phoneValId, industrySectorValId: industrySectorValId, industryLocationValId: industryLocationValId, leadSourceValId: leadSourceValId, leadStageValId: leadStageValId, industryEngagementValId: industryEngagementValId },
                    success: function(response) {
                        $.notify("HR details added successfully.", "success");
                        $("#companyLeadFormId").hide();
                        $("#compSearch").prop("disabled", false);
                        $("#btnShow").prop("disabled", false);
                        showDetails(response.mesg);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.mesg);
                    }
                });
            }
            else {
                e.preventDefault();
                return false;
            }
            e.preventDefault();
        });

        function getSchool() {
        var entity_id = $('#entityId').val();
       
        $.ajax({
            url: "/get-school",
            type: "GET",
            data: { entity_id: entity_id },
            success: function(data) {
                
                var schoolId = $("#schoolId").empty();
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

    function getCourse() {
        var school_id = $('#schoolId').val();
        $.ajax({
            url: "/get-course",
            type: "GET",
            data: { school_id: school_id },
            success: function(data) {
                
                var courseId = $("#courseId").empty();
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
            
    </script>

@endsection