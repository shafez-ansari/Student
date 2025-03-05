@extends('cro.cro-master')

@section('cro-content')

<style>
        /* General Styles */
        body {
    font-family: Arial, sans-serif;
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
        }

        /* Action Buttons */
        .action-buttons {
            gap: 20px;
            margin: 20px 0;
        }

        .action-buttons button {
            width: auto;
            background-color: #ff0000;
            color: #ff0000;
            border: 1px solid #ff0000;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .action-buttons button:hover {
            background-color: #ff0000;
            color: white;
        }

        /* Upload Section */
        .upload-section {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
        }

        .upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ccc;
            margin: 40px auto;
            padding: 40px;
            max-width: 700px;
            border-radius: 10px;
            background-color: #f9f9f9;
            color: #333;
        }

        .upload-area img {
            width: 80px;
            margin-bottom: 10px;
        }

        .upload-area p {
            font-size: 16px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .logo-row {
                flex-wrap: wrap;
                gap: 20px;
            }

            .progress-bar {
                flex-wrap: wrap;
                gap: 10px;
            }

            .progress-bar hr {
                width: 50px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .upload-area {
                width: 90%;
            }
        }
    </style>
    <!-- <div class="action-buttons">
        <button onclick="alert('View Data functionality coming soon!')">View Data</button>
        <button onclick="alert('Upload Data functionality coming soon!')">Upload Data</button>
    </div> -->
    <div class="upload-section">
        Upload Image
    </div>
    <div id="loader" class="loader" style="display: none;"></div>
    <div class="upload-area">
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf            
            <img src="{{url('/images/bul.png')}}" alt="Upload Icon">
            <p>Upload Bulk Images</p>                    
            <input type="file" name="studentFile[]" id="studentFile" accept=".jpg, .jpeg" multiple>
            <span class="text-danger" id="studentValidationId"></span>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

<script type="text/javascript">
   
    $('#uploadForm').on('submit', function(e) {
    e.preventDefault();

    let files = document.getElementById('studentFile').files;
        if (files.length === 0) {
            $('#studentValidationId').html('Please select at least one file to upload.');
            return;
        }
        $('#loader').show();
        let validExtensions = ['jpg', 'jpeg'];
        let formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        
        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            let fileExt = file.name.split('.').pop().toLowerCase();

            if (!validExtensions.includes(fileExt)) {
                $('#studentValidationId').html('Please upload images with .jpg or .jpeg extension only.');
                $('#loader').hide();
                return;
            }

            formData.append('studentFile[]', file);
        }

        // Clear the error message
        $('#studentValidationId').html('');
    $.ajax({
        url: "/save-image",
        method: "POST",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#loader').hide();
            $.notify(response.message, "success");
            setTimeout(function(){
                    location.reload(); 
                    },2000);
        },
        error: function(xhr) {
            $('#loader').hide();
            alert(xhr.responseJSON.message);
        }
    });
    });
</script>

    @endsection

