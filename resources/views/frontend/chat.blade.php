@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php $lastSegment = last(explode('/', url()->current())); ?>
<style>
        .chat-box {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .message {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 10px;
        }
        .message.sender {
            background-color: #d1e7dd;
            text-align: right;
        }
        .message.receiver {
            background-color: #f8d7da;
        }
    </style>

<main id="main-page">
<!-- new design  -->
    @if($lastSegment == 0)
        <div class="back-to"><a href="<?php if($user_type == 'doctor') echo URL('/').'/doctor-dashboard/past'; else echo URL('/').'/patient-dashboard/past';?>">
            Back to Past Appointments</a>
        </div>
    @else
        <div class="back-to"><a href="{{ url('/new-messages') }}">
            Back to Messages</a>
        </div>
    @endif
    <div class="message-box">
                <div class="message-box-header">
                    <div class="patients-list w-100 gap-4">
                        <div class="profile-menu"> 
                            <a class="nav-link p-0" href="#">
                            <div class="profile-pic">
                                    @if($user_data->user_type == 'doctor')
                                        <img src="{{ url('/public/patient_photos') }}/{{ $opp_user_data->profile_photo }}" alt="Profile Picture">
                                    @else
                                        <img src="{{ url('/public/doctor_photos') }}/{{ $opp_user_data->profile_photo }}" alt="Profile Picture">
                                    @endif
                                
                                
                                
                                <img src="{{ url('/public/frontend/img') }}/Group 9799.png" alt="">
                                
                            </div> 

                            @if($user_type == 'patient')
                                <span class="d-none d-md-flex">{{ 'Dr.' }} {{ $opp_user_data->first_name }} {{ $opp_user_data->last_name }}</span>
                            @else
                                <span class="d-none d-md-flex">{{ $opp_user_data->first_name }} {{ $opp_user_data->last_name }}</span>
                            @endif
                                <!-- You can also use icon as follows: -->
                                    <!--  <i class="fas fa-user"></i> -->
                            </a>
                        </div>
                        <ul class="list-one"><li><span>Date</span>{{ date("d M Y", strtotime($appointment->start)) }}</li>
                            <li><span>Time</span>{{ date("G:i", strtotime($appointment->start)) }} </li>
                            <li><span>Category </span>{{ $appointment->category }}</li>
                        </ul>
            
                    </div>
                </div>
                
                @if(!$isWithin24Hours)
                <div  id="chatBox" class="chat-box message-box-body disabled">
                @else             
                <div  id="chatBox" class="chat-box message-box-body enabled">             
                @endif    

                </div>

                <div class="message-box-footer">
                    @if($user_data->user_type == 'patient' && !$isWithin24Hours)
                            <div class="conversation-box text-center"><p class="text-danger">This conversation is disabled. (You can chat only for 24 hours post appointment)</p><p>For booking a new appointment click on the button</p>
                                <a href="{{url('/book_appointment') }}" class="btn btn-orange">Book Now</a>
                            </div>
                    @endif

                    <div class="uploaded-files d-flex flex-column gap-2 mb-3">
                        <!-- Uploaded files will be dynamically inserted here -->
                    </div>
            
                    @if($user_data->user_type == 'patient' && $isWithin24Hours)
                    <div class="message-form">  
                        <form id="chatForm">
                                <input type="hidden" id="app_id" name="app_id" value="{{ $appointment->id }}">
                                <textarea id="messageInput" name="" placeholder="Type here..." required></textarea>
                                <button type="button"><input type="file" id="medicalDocuments" name="medicalDocuments[]" multiple><img src="{{ url('/public/frontend/img/Group 9851.png') }}"></button>
                                <button id="SubmitBtn" type="submit"><img src="{{ url('/public/frontend/img/Group 9761.png') }}"></button>
                        </form>
                    </div>
                    @elseif($user_data->user_type == 'doctor')
                        <div class="message-form">  
                            <form id="chatForm">
                                    <input type="hidden" id="app_id" name="app_id" value="{{ $appointment->id }}">
                                    <textarea id="messageInput" name="" placeholder="Type here..." required></textarea>
                                    <button type="button"><input type="file" id="medicalDocuments" name="medicalDocuments[]" multiple><img src="{{ url('/public/frontend/img/Group 9851.png') }}"></button>
                                    <button type="submit"><img src="{{ url('/public/frontend/img/Group 9761.png') }}"></button>
                            </form>
                        </div>
                    @endif

                </div>
    </div>
</main>
</div>

<!-- Jquery -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script> -->
<!-- Bootstrap 5 JS Bundle -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script> -->

<script>
    $(document).ready(function() {
        // $('#messageInput').on('focus', function() {
        //     if (!$(this).val().startsWith(" ")) {
        //         $(this).val(" " + $(this).val());
        //     }
        // });

        $('html, body').animate({
            scrollTop: $(document).height()
        }, 1000);

        let chatId = "{{ $chat_id }}"; // Assuming $chat is passed to the view

        // Load existing messages on page load
        loadMessages();

        // Poll for new messages every 5 seconds
        setInterval(loadNewMessages, 5000); // Adjust the interval as needed

        // AJAX call to send a new message
        $('#chatForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData();
            let message = $('#messageInput').val();
            let app_id = $('#app_id').val();
            let medicalDocuments = $('#medicalDocuments')[0].files;
            $('#SubmitBtn').prop('disabled', true);

            // Append each file to the FormData object
            for (let i = 0; i < medicalDocuments.length; i++) {
                formData.append('medicalDocuments[]', medicalDocuments[i]);
            }

            formData.append('message', message);
            formData.append('app_id', app_id);
            // Append the CSRF token to the FormData
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: `{{ URL('/') }}/chat/${chatId}/message`,
                type: 'POST',
                data: formData,
                processData: false,  // Prevent jQuery from converting the data
                contentType: false,  // Ensure multipart/form-data is used
                success: function(response) {

                    const uploadedFilesContainer = document.querySelector('.uploaded-files');
                    uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

                    $('#messageInput').attr('required', 'required');
                    $("#medicalDocuments").val('');
                    PushedImg = [];
                    
                    $('#SubmitBtn').prop('disabled', false);

                    $('#messageInput').val('');  // Clear input                    
                    appendMessage(response, true);  // Add message to chat box
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        });

        // Function to load messages
        function loadMessages() {
            $.ajax({
                url: `{{ URL('/') }}/chat/${chatId}/messages`,
                type: 'GET',
                success: function(response) {
                    response.forEach(function(message) {
                      
                        @php
                            // Retrieve the 'user' array from the session
                            $user = session('user',[]);
                        @endphp

                        appendMessage(message, message.sender_id == '{{ $user["id"] }}');
                    });
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        }

        // Function to append a message to the chat box
        function appendMessage(message, isSender) {           

            let messageClass = isSender ? 'align-items-end' : 'align-items-start';
            let messageTextClass = isSender ? 'from-message' : 'to-message';

            const date = new Date(message.created_at);

            // Define options to format the time without seconds
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true // Set to true for 12-hour format (AM/PM)
            };

            // Format the time
            const formattedTime = date.toLocaleTimeString('en-US', options);

            if(message.msg_flag == 1){
                var tick_img = `<img src="{{ url('/public/frontend/img') }}/Group 9799.png" alt="">`;
            } else {
                var tick_img = `<img src="{{ url('/public/frontend/img') }}/Group 9800.png" alt="">`;
            }

            if(message.message == '' && message.files !==  '0'){
                var medicalDocumentsArray = message.files.split(",");
                const files = medicalDocumentsArray;

                var display_file = ``;

                Array.from(files).forEach(file => {
                    const fileName = file; // You are assigning 'file' to 'fileName', but this variable isn't used, you can omit this line.
                    display_file += `<a href="{{ url('/public/chat_files/${fileName}') }}" target="_blank">${fileName}</a><br>`;
                });

                var messageElement = `
                <div id="message-${message.id}"  class="1message d-flex justify-content-end flex-column gap-1 mb-3 ${messageClass}">
                    <div class="${messageTextClass}">${display_file}</div>
                    <div class="d-flex justify-content-between align-items-center gap-2">${formattedTime} ${tick_img}</div>
                </div>`;
            } 
            
            if(message.files === '0' && message.message != ''){
                var messageElement = `
                <div id="message-${message.id}"  class="2message d-flex justify-content-end flex-column gap-1 mb-3 ${messageClass}">
                    <div class="${messageTextClass}">${message.message}</div>
                    <div class="d-flex justify-content-between align-items-center gap-2">${formattedTime} ${tick_img}</div>
                </div>`;
            } 
            
            if(message.files !== '0' && message.message != ''){
                var messageElement = `
                <div id="message-${message.id}"  class="3message d-flex justify-content-end flex-column gap-1 mb-3 ${messageClass}">
                    <div class="${messageTextClass}">${message.message}</div>
                    <div class="d-flex justify-content-between align-items-center gap-2">${formattedTime} ${tick_img}</div>
                </div>`;
                if(message.files !== '0'){

                    var medicalDocumentsArray = message.files.split(",");
                    const files = medicalDocumentsArray;

                    var display_file = ``;

                    Array.from(files).forEach(file => {
                        const fileName = file; // You are assigning 'file' to 'fileName', but this variable isn't used, you can omit this line.
                        display_file += `<a href="{{ url('/public/chat_files/${fileName}') }}" target="_blank">${fileName}</a><br>`;
                    });

                    messageElement += `
                    <div id="message-${message.id}"  class="4message d-flex justify-content-end flex-column gap-1 mb-3 ${messageClass}">
                        <div class="${messageTextClass}">${display_file}</div>
                        <div class="d-flex justify-content-between align-items-center gap-2">${formattedTime} ${tick_img}</div>
                    </div>`;
                }
            }

            $('#chatBox').append(messageElement);
    
            $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);  // Scroll to bottom
        }

         // Function to load new messages
        function loadNewMessages() {
            $.ajax({
                url: `{{ URL('/') }}/chat/${chatId}/messages`, // Adjust this URL to only fetch messages after the last received message
                type: 'GET',
                success: function(response) {
                    response.forEach(function(message) {
                        if (!$(`#message-${message.id}`).length) { // Check if message already exists
                            appendMessage(message, message.sender_id == '{{ $user["id"] }}');
                        }
                    });
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
            }

    });

    var PushedImg = [];

    document.addEventListener('DOMContentLoaded', function() {
        var fileInput = document.getElementById('medicalDocuments');
        const uploadedFilesContainer = document.querySelector('.uploaded-files');
        
        fileInput.addEventListener('change', function() {
            const files = fileInput.files;
            $('#messageInput').removeAttr('required');
            // uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

            Array.from(files).forEach(file => {

                if (file.size > 4096 * 1024) {
                    alert('The image/file size must not be greater than 5Mb');
                    return false;
                }

                PushedImg.push(file);

                var dataTransfer = new DataTransfer();

                if(PushedImg != ''){
                    for (var i = 0; i < PushedImg.length; i++) {
                        dataTransfer.items.add(PushedImg[i]);
                    }

                    // Set the files to the input field
                    $('#medicalDocuments')[0].files = dataTransfer.files;
                }

                // const dataTransfer = new DataTransfer();
                // dataTransfer.items.add(file);
                // fileInput.files = dataTransfer.files;



                const fileName = file.name;

                // Create a new file item
                const fileItem = document.createElement('div');
                fileItem.className = 'd-flex gap-2 align-items-center';
                
                const closeButton = document.createElement('a');
                closeButton.href = '#';
                closeButton.className = 'close';
                closeButton.innerHTML = `<img src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;
                closeButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    // Find the index of the file in the PushedImg array
                    const index = PushedImg.indexOf(file);
                    if (index > -1) {
                        PushedImg.splice(index, 1); // Remove the file from the array
                    }

                    // Update the input field files
                    dataTransfer.clearData();
                    PushedImg.forEach(file => dataTransfer.items.add(file));
                    $('#medicalDocuments')[0].files = dataTransfer.files;

                    fileItem.remove();
                });

                const fileLink = document.createElement('a');
                fileLink.href = '#';
                fileLink.textContent = fileName;

                fileItem.appendChild(closeButton);
                fileItem.appendChild(fileLink);
                uploadedFilesContainer.appendChild(fileItem);
            });
        });
    });
</script>

</body>
</html>
@endsection