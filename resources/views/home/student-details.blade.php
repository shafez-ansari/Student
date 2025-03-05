@extends('home.home-master')

@section('content')

<style>
/* Custom Checkbox Styling */
.custom-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background-color: #f0f0f0;
    border: 2px solid #ccc;
    border-radius: 5px;
    width: 80px;
    height: 40px;
    text-align: center;
    transition: all 0.3s ease;
}

.custom-checkbox input[type="checkbox"] {
    display: none;
}

.custom-checkbox span {
    font-weight: bold;
    color: #555;
}

.custom-checkbox:hover {
    border-color: #999;
}

.custom-checkbox input[type="checkbox"]:checked + span {
    background-color: #ff0000;
    color: #fff;
    padding: 5px 15px;
    border-radius: 5px;
}

/* Card and Table Styling */
.card {
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.35);
    border-radius: 10px;
    padding: 20px;
    max-width: 800px;
    margin: 30px auto;
    font-family: Arial, sans-serif;
}

.card h5 {
    text-transform: uppercase;
    text-align: Left;
    background-color: #333;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
}

.card table {
    width: 100%;
    border-collapse: collapse;
}

.card td {
    padding: 8px;
}

.card img {
    border-radius: 50%;
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.card label {
    cursor: pointer;
    position: unset;
    bottom: -60px;
    right: 80px;
    background-color: #fff;
    border-radius: 50%;
    padding: 6px;
    box-shadow: 0px 2px 4px rgba(0,0,0,0.35);
}

/* Placement Section */
.placement-section {
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.35);
    border-radius: 10px;
    padding: 20px;
    max-width: 800px;
    text-align: left;
    margin: 30px auto;
    margin-top: 40px;
}

.placement-section h5 {
    text-transform: uppercase;
    text-align: Left;
    background-color: #333;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
}

.placement-section .checkbox-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.placement-section .submit-btn {
    background-color: #ff0000;
    border: none;
    color: white;
    padding: 10px 40px;
    border-radius: 5px;
    margin-top: 10px;
    cursor: pointer;
}

.placement-section .error-msg {
    color: red;
}

/* Scrollable Table */
.table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* for smooth scrolling on iOS */
}

@media screen and (max-width: 768px) {
    .card {
        padding: 15px;
        max-width: 100%;
    }

    .card img {
        width: 100px;
        height: 100px;
    }

    .placement-section {
        padding: 15px;
        max-width: 100%;
    }

    .placement-section .checkbox-container {
        align-items: flex-start;
    }

    .placement-section .submit-btn {
        width: 100%;
        padding: 12px;
    }

    .card table td {
        font-size: 12px;
    }

    /* Make the table scrollable on mobile view */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    /* Student Detail Section - Mobile-Friendly */
    .student-detail-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-top: 20px;
    }

    .student-detail-wrapper .student-image {
        width: 80px;
        height: 80px;
    }

    .student-detail-wrapper .student-info {
        text-align: left;
        margin-top: 10px;
        width: 100%;
    }

    .student-detail-wrapper .student-info table {
        width: 100%;
    }

    .student-detail-wrapper .student-info td {
        padding: 8px;
    }
}

@media screen and (min-width: 769px) {
    /* For Desktop and Tablet */
    .student-detail-wrapper {
        display: flex;
        flex-direction: row; /* Horizontal layout for desktop/tablet */
        align-items: flex-start;
        justify-content: flex-start;
    }

    .student-detail-wrapper .student-image {
        margin-right: 0px;
    }

    .student-detail-wrapper .student-info {
        text-align: left;
        width: 70%;
    }

    .student-detail-wrapper .student-info table {
        width: 100%;
    }

    .student-detail-wrapper .student-info td {
        padding: 8px;
    }
}

@media screen and (max-width: 480px) {
    .card {
        padding: 10px;
    }

    .placement-section {
        padding: 10px;
    }

    .card img {
        width: 80px;
        height: 80px;
    }

    .placement-section .checkbox-container {
        gap: 10px;
    }

    .placement-section .submit-btn {
        padding: 15px;
    }

    .card table td {
        font-size: 10px;
    }
}

</style>

<!-- Student Details Card -->
<div class="card">
    <h5>Student Details</h5>
    <div class="student-detail-wrapper">
        <div style="text-align: center; flex: 1; padding-top: 25px; position: relative;">
            
            <!-- Student Image -->
            @foreach($userList as $user)
                @if($user->img_name != null)
                    <img id="studentImage" class="student-image" src="{{ asset($user->img_path) }}" alt="Student Avatar">
                @else
                    <img id="studentImage" class="student-image" src="{{url('/images/user.jpg')}}" alt="Student Avatar">
                @endif
            @endforeach
            <!-- Edit Image Icon -->
            <!-- <label for="uploadImage" style="cursor: pointer; position: unset; bottom: -60px; right: 80px; background-color: #fff; border-radius: 50%; padding: 6px; box-shadow: 0px 2px 4px rgba(0,0,0,0.35);">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#555">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34c-.39-.39-1.02-.39-1.42 0L15.13 4.5l3.75 3.75 1.83-1.83z"></path>
                </svg>
            </label> -->
            <input type="file" id="uploadImage" accept="image/*" style="display: none;" onchange="previewImage(event)">
        </div>
        
        <div class="student-info">
            <div class="table-wrapper">
                <table style="width: 100%; border-collapse: collapse;">
                    @foreach($userList as $user)
                    <tr>
                        <input type="hidden" value="{{ $user->entity_name }}" id="hdnEntity" />
                        <input type="hidden" value="{{ $user->school_name }}" id="hdnSchool" />
                        <input type="hidden" value="{{ $user->course_name }}" id="hdnCourse" />
                        <td style="font-weight: bold; padding: 8px;">Entity Name:</td>
                        <td style="padding: 8px;">{{ $user->entity_name }}</td>
                        <td style="font-weight: bold; padding: 8px;">School:</td>
                        <td style="padding: 8px;">{{ $user->school_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px;">Unique ID:</td>
                        <td style="padding: 8px;">{{ $user->unique_id }}</td>
                        <td style="font-weight: bold; padding: 8px;">Program Name:</td>
                        <td style="padding: 8px;">{{ $user->course_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px;">Name:</td>
                        <td style="padding: 8px;">{{ $user->full_name }}</td>
                        <td style="font-weight: bold; padding: 8px;">Batch Code:</td>
                        <td style="padding: 8px;">{{ $user->batch_code }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px;">Email:</td>
                        <td style="padding: 8px;">{{ $user->email }}</td>
                        <td style="font-weight: bold; padding: 8px;">Semester:</td>
                        <td style="padding: 8px;">{{ $user->batch_code }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px;">Contact:</td>
                        <td style="padding: 8px;">{{ $user->phone }}</td>
                        <td style="font-weight: bold; padding: 8px;">Enrollment Date:</td>
                        <td style="padding: 8px;">{{ $user->enrollment_date }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Placement Section -->
<div class="placement-section">
    <h5>Need Placement?</h5>
    <div class="checkbox-container">
        <!-- Yes Checkbox -->
        <label class="custom-checkbox">
            <input type="checkbox" id="yes" onchange="toggleCheckbox('yes')">
            <span>Yes</span>
        </label>
        <!-- No Checkbox -->
        <label class="custom-checkbox">
            <input type="checkbox" id="no" onchange="toggleCheckbox('no')">
            <span>No</span>
        </label>
    </div>
    <!-- <div style="text-align: center; width:750; margin-top: 20px;">
        <span id="yesornoerror" class="error-msg"></span>
        <button type="button" onclick="submitPlacement()" class="submit-btn">Submit</button>
    </div> -->
</div>

<!-- Delete Agency Modal -->
<div class="modal" id="submitPlacementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="deleteTitleId"><b>Confirmation</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="yesId" name="yesId" /> 
        <input type="hidden" id="noId" name="noId" />       
        <p id="deleteMesgId">Are you sure you want to continue?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" style="width:10%; background-color:green" title="Yes" onclick="submitPlacement();">Yes</button>
        <button data-bs-dismiss="modal" style="width:10%; background-color:#ff0000"  class="btn btn-danger" title="No">No</button>
        </div>
    </div>    
  </div>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    function toggleCheckbox(selected) {
        
        if (selected === 'yes') {
            document.getElementById('no').checked = false;
            $("#yesId").val('1');
            $("#noId").val('0');
        } else if (selected === 'no') {
            document.getElementById('yes').checked = false;
            $("#yesId").val('0');
            $("#noId").val('1');
        }

        $("#submitPlacementModal").modal('show');
    }

    function submitPlacement() {
        
        const yesChecked = document.getElementById('yes').checked;
        const noChecked = document.getElementById('no').checked;
        const errorElement = document.getElementById('yesornoerror');
        entity = document.getElementById('hdnEntity').value;
        school = document.getElementById('hdnSchool').value;
        course = document.getElementById('hdnCourse').value;

        // if ($('#yes').is(':checked')) {
        //     yes = 1;
        // }
        // else {
        //     yes = 0;
        // }
        // if ($('#no').is(':checked')) {
        //     no = 1;
        // }
        // else {
        //     no = 0;
        // }
        yes = $("#yesId").val();
        no = $("#noId").val();
        //errorElement.textContent = '';

        
            let url = `/store-placement/${entity}/${school}/${yes}/${no}/${course}`;
            window.location.href = url;
        
    }
</script>

@endsection
