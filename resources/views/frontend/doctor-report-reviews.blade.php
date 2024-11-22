@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<style>
    .patent-reviews02{background:#FFEFE5;border-radius:10px; position:relative;}
.btn-disabled {
  background: #848484;
  cursor: not-allowed !important;
}
.btn-disabled:hover,.btn-disabled:focus {
  background: #848484;
  cursor: not-allowed !important;
}
.btn-uploads-doc input[type="file"]{opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;}
.small_cls{
  font-size: 15px;
}
</style>
<!-- Modal -->

<div class="modal fade" id="reply-patientModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reply To Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="replyForm" action="{{ route('doctor.report-reply') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="appointment_id" id="appointment_id">
          <div class="mb-3">
            <textarea class="form-control" name="reply" placeholder="Enter your reply here..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document
              <input type="file" name="upload_file1[]" id="medicalDocuments" multiple style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div>
          <div class="uploaded-files-new d-flex flex-column gap-2">
              <!-- Uploaded files will be dynamically inserted here -->
          </div>

          <!-- <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document-2
              <input type="file" name="upload_file2" id="upload_file2" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document-3
              <input type="file" name="upload_file3" id="upload_file3" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div> -->
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Reply</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modifyreply-patientModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Modify Reply To Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="modifyreplyForm" action="{{ route('doctor.modify-report-reply') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="modify_appointment_id" id="modify_appointment_id">
          <input type="hidden" name="modify_reply_id" id="modify_reply_id">
          <div class="mb-3">
            <input type="hidden" id="ExtmedicalDocuments" value=""/>
            <textarea class="form-control" name="modify_reply_text" id="modify_reply_text"  placeholder="" style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div> 
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document
              <input type="file" name="modify_upload_file1[]" id="modify_upload_file1" multiple style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div> 
          <div class="uploaded-files d-flex flex-column gap-2">
              <!-- Uploaded files will be dynamically inserted here -->
          </div>      
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Reply</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<main id="main-page">

<div class="">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                            
                                        </ul>
                                        
                                    </div>
                                    @endif
                                
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    
                             </div>        
                             


<div class="book-bg">
  <ul>
      <li class="<?= $result['record_type'] == 'report_review' ? 'active' : '' ?>">
        Report Reviews
      </li>     
  </ul>  
  <!-- <a href="{{ url('/') }}/book_appointment" class="btn btn-book d-flex align-items-center gap-2"><img src="{{ url('/public/frontend/img/Layer 7.png') }}"> Book an Appointment</a> -->
</div>
<form id="reportreviewForm" action="{{ url('/') }}/doctor-report-reviews" method="POST">
  @csrf 
  <div class="row mt-3">
    <div class="col-md-3 mb-3">
      <label for="start">{{__('Start Date')}}</label>
      <!-- <input type='date' class='form-control small_cls' id='start' name='start' required value='{{ $start }}'> -->
      <input type="text" class='form-control small_cls' name="start" id="start-picker" placeholder="mm-dd-yyyy" required value='{{ @$start }}'> 

    </div>
    <div class="col-md-3 mb-3">
      <label for="end">{{__('End Date')}}</label>
      <!-- <input type='date' class='form-control small_cls' id='end' name='end' required value='{{ $end }}'> -->
      <input type="text" class='form-control small_cls' name="end" id="end-picker" placeholder="mm-dd-yyyy" required value='{{ @$end }}'> 

    </div>
    <div class="col-md-3 mb-3">
      <label>Status</label>                       
      <select class="form-select small_cls" id="status" name="status">
          <option value="">Select Status</option>
          <option value="Replied" <?php if($status == 'Replied') { echo 'selected'; } ?>>Replied</option>
          <option value="Not-Replied" <?php if($status == 'Not-Replied') { echo 'selected'; } ?>>Not Replied</option>
      </select>
    </div>
    <div class="col-md-3 mb-3" style="margin-top: 32px;">
      <button type="submit" name="btnSubmit" id="submit" class="btn btn-orange border-radius-0">Submit</button>
      <a href="{{ url('/') }}/doctor-report-reviews" style="background:#02C4B7" class="btn btn-success border-radius-0">Reset</a>
    </div>
  </div>
</form>
    
<?php 
if(!empty($result['appointments_booked']) && $result['appointments_booked']->isNotEmpty())
{ $i =0;
?>

<div class="accordion row m-0" id="accordionExample">
  <?php
  foreach($result['appointments_booked'] as $k=>$appointment)    
  {  $i++;
  ?>
    <div class="col-12 col-md-12 col-lg-12 p-0 d-flex align-items-stretch">
      <div class="p-0 w-100 mt-3">
        <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
          <h2 class="accordion-header">
            <button class="accordion-button <?= ($i == 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
              <div class="patients-list mb-0 me-3 w-100">
                <ul class="list-one"><li><span>Requested On</span><?= date('j M Y G:i', strtotime($appointment->created_at)) ?></li>
                
                <li><span>Category </span>{{ $appointment->category }}</li>
                <li><span>Patient</span>{{ $appointment->patient_name }}</li>
                </ul><span class="confirm">{{ $appointment->appointment_status }}</span>

              </div>
            </button>
          </h2>
          <div id="collapse_{{ $appointment->id }}" class="accordion-collapse collapse <?= ($i == 1) ? 'show' : '' ?>" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <div class="summries">
                <div class="row">
                  <div class="col-12 col-md-9 col-lg-10">
                    <div class="row">
                      <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                          <span>Patient Name : </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $appointment->patient_name }}</div></div></div></div>
                      <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                          <span>Charges :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">${{ $appointment->amount }} </div></div>
                          </div></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>Appointment Type :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                          <div class="mb-3">{{ $appointment->appointmentType }}</div></div></div></div>
                          <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                              <span>Contact Number :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">
                                {{ formatPhoneNumber($appointment->patient_phone_number) }}</div></div></div></div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Joints of Interest : </span></div></div>
                        <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->interests }}</div></div></div></div>
                    </div>
                    
                    <div class="row">
                      <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Symptoms : </span></div></div>
                      <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->symptoms }}</div></div></div></div>
                    </div>

                    @if($appointment->notes != '')
                      <div class="row">
                        <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Note : </span></div></div>
                        <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->notes }}</div></div></div></div>
                      </div>
                    @endif
                    <div class="row">
                      <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2">
                          <div class="mb-0"><span>Documents/<br>Reports : </span></div></div><div class="col-12 col-md-9 col-lg-10"><div class="mb-0"><div class="d-inline-flex gap-1 flex-column">
                              <?php
                              if(!empty($appointment->medicalDocuments))
                              {
                                  
                              $reports = explode(",",$appointment->medicalDocuments);
                              foreach($reports as $k=>$v)
                              {
                              ?>
                              <a target="_blank" href="{{ url('/') }}/public/patient_reports/{{ $v }}">{{ $v }}</a>
                              <?php
                              }
                              }
                              ?>
                              </div></div></div></div></div>
                    </div>
                  </div>
                  
                  <div class="col-12 col-md-3 col-lg-2 reply_button" data-appointment-id="{{ $appointment->id }}">
                      <!-- <a href="#" class="btn btn-orange btn-disabled w-100 border-radius-0 mb-3">Reply</a> -->
                                        
                      @if($appointment->appointment_status == "Not-Replied")
                      <button style="background:#02C4B7" type="button" class="btn btn-success w-100 border-radius-0" 
                              data-bs-toggle="modal" 
                              data-bs-target="#reply-patientModal"
                              data-appointment-id="{{ $appointment->id }}">
                        Reply to Report
                      </button>
                      @endif   

                      </div>
                </div>
                @foreach($appointment->reportReviewsReplies as $k2=>$reply)
                <div class="patent-reviews02 p-3 mt-3 pb-0">
                    <div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>
                      Replied On : </span></div></div>
                      <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">
                      <?= date('j M Y H:i', strtotime($reply->created_at)) ?>  
                      </div></div></div>
                      <div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>
                      Doctor’s Response : </span></div></div>
                      <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">
                      @if ($reply->doctor_reply != '')
                        {{ Crypt::decrypt($reply->doctor_reply) }}
                      @endif
                      </div></div></div>
                      <div class="row">
                        <div class="col-12 col-md-3 col-lg-2">
                          <div class="mb-3">
                          <span>Doctor’s Uploads : </span>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-8">
                          <div class="mb-3">
                        
                        @if(!empty($reply->upload_file1))
                        @foreach(explode(',', $reply->upload_file1) as $file)
                            <a target="_blank" href="{{ URL('/') }}/public/patient_reports/{{ trim($file) }}">{{ trim($file) }}</a><br>
                        @endforeach
                        @endif
                        
                          </div>
                        </div>

                        <div class="col-12 col-md-3 col-lg-2" style="">
                          <div class="mb-3"> 
                              <button type="button" class="btn btn-orange w-100 border-radius-0" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modifyreply-patientModal"  
                                  data-modify-appointment-id="{{ $appointment->id }}" 
                                  data-modify-reply-id = "{{ $reply->id }}" 
                                  data-modify-reply-text="@if ($reply->doctor_reply != ''){{ Crypt::decrypt($reply->doctor_reply) }}@endif" 
                                  data-modify-medical-documents-id="{{ $reply->upload_file1 }}" 
                                  >                              
                                  Modify Reply
                              </button>                   
                          </div>
                        </div>

                      
                      </div>
                </div>
                @endforeach
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
  <?php
  }
  ?>
</div>
<?php
}
else
{
?>
<div class="row m-0 No_Records_Found">
<div class="col-12 col-md-12 col-lg-12 p-4 d-flex align-items-stretch">
    <h6>No Records Found </h6>
</div>
</div>

<?php    
}
?>
</main>
</div>


<script>

$(function() {
        $("#start-picker").datepicker({
            dateFormat: "mm-dd-yy" // Set the date format
        });
        $("#end-picker").datepicker({
            dateFormat: "mm-dd-yy" // Set the date format
        });
    });
  // This script assumes you're using jQuery, but it can be adapted to plain JavaScript
  $(document).ready(function() {
    $('#reply-patientModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var appointmentId = button.data('appointment-id'); // Extract info from data-* attributes
      var modal = $(this);
      modal.find('#appointment_id').val(appointmentId);
    });

    $('#modifyreply-patientModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var modify_appointment_id = button.data('modify-appointment-id'); // Extract info from data-* attributes
      var modify_reply_id = button.data('modify-reply-id'); // Extract info from data-* attributes
      var ext_medical_doc = button.data('modify-medical-documents-id');
      
      var modify_reply_text = button.data('modify-reply-text'); // Extract info from data-* attributes
      
      var modal = $(this);

      modal.find('#modify_appointment_id').val(modify_appointment_id);
      modal.find('#modify_reply_text').val(modify_reply_text);
      modal.find('#ExtmedicalDocuments').val(ext_medical_doc);
      modal.find('#modify_reply_id').val(modify_reply_id);

      const uploadedFilesContainer = document.querySelector('.uploaded-files');
      const serverpath = "{{ URL('/') }}/public/patient_reports/";
      var ExtmedicalDocuments = $('#ExtmedicalDocuments').val();

        if(ExtmedicalDocuments != null && ExtmedicalDocuments != '' && typeof ExtmedicalDocuments !== 'undefined'){
            var medicalDocumentsArray = ExtmedicalDocuments.split(",");
            var fileInput = medicalDocumentsArray;
            
            const files = fileInput;
            uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

            Array.from(files).forEach(file => {
              
                const fileName = file;

                // Create a new file item
                const fileItem = document.createElement('div');
                fileItem.className = 'd-flex gap-2 align-items-center';
                
                const closeButton = document.createElement('a');
                closeButton.href = '#';
                closeButton.className = 'close extClose';
                closeButton.innerHTML = `<img class="existing_files" data-filename="${fileName}" src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;
                
                const fileLink = document.createElement('a');
                fileLink.href = `${serverpath}${fileName}`;
                fileLink.textContent = fileName;
                fileLink.target = "_blank";  
                fileItem.appendChild(closeButton);
                fileItem.appendChild(fileLink);
                uploadedFilesContainer.appendChild(fileItem);

                closeButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    

                    const filename = this.querySelector('img').getAttribute('data-filename');
                    const replyId = button.data('modify-reply-id'); 

                    // Perform AJAX request to delete the file
                    fetch('{{ route("delete.file") }}', {
                    method: 'POST',
                    headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                    reply_id: replyId,
                    filename: filename
                    })
                    })
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                      alert('File deleted successfully.');
                      fileItem.remove();
                    } else {
                    alert('Failed to delete file.');
                    }
                    })
                    .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the file.');
                    });

                });

            });
        }
    });

  });

    document.addEventListener('DOMContentLoaded', function() {
      var fileInput = document.getElementById('medicalDocuments');
      const uploadedFilesContainer = document.querySelector('.uploaded-files-new');
      let PushedImg = []; // Ensure PushedImg is declared.

      fileInput.addEventListener('change', function() {
          const files = fileInput.files;

          // Iterate over the files and add them to PushedImg
          Array.from(files).forEach(file => {
              PushedImg.push(file);

              var dataTransfer = new DataTransfer();

              // Re-add files from PushedImg to the input
              PushedImg.forEach(imgFile => dataTransfer.items.add(imgFile));

              // Set the files to the input field
              fileInput.files = dataTransfer.files;

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
                  
                  // Remove file from PushedImg array
                  const index = PushedImg.indexOf(file);
                  if (index > -1) {
                      PushedImg.splice(index, 1); // Remove the file from the array
                  }

                  // Create a new DataTransfer and update with the new PushedImg array
                  var updatedDataTransfer = new DataTransfer();
                  PushedImg.forEach(imgFile => updatedDataTransfer.items.add(imgFile));

                  // Set the updated files to the input field
                  fileInput.files = updatedDataTransfer.files;

                  // Remove the file item from the display
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

    document.addEventListener('DOMContentLoaded', function() {
      var fileInput = document.getElementById('modify_upload_file1');
      const uploadedFilesContainer = document.querySelector('.uploaded-files');
      let PushedImg = []; // Ensure PushedImg is declared.

      fileInput.addEventListener('change', function() {
          const files = fileInput.files;

          // Iterate over the files and add them to PushedImg
          Array.from(files).forEach(file => {
              PushedImg.push(file);

              var dataTransfer = new DataTransfer();

              // Re-add files from PushedImg to the input
              PushedImg.forEach(imgFile => dataTransfer.items.add(imgFile));

              // Set the files to the input field
              fileInput.files = dataTransfer.files;

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
                  
                  // Remove file from PushedImg array
                  const index = PushedImg.indexOf(file);
                  if (index > -1) {
                      PushedImg.splice(index, 1); // Remove the file from the array
                  }

                  // Create a new DataTransfer and update with the new PushedImg array
                  var updatedDataTransfer = new DataTransfer();
                  PushedImg.forEach(imgFile => updatedDataTransfer.items.add(imgFile));

                  // Set the updated files to the input field
                  fileInput.files = updatedDataTransfer.files;

                  // Remove the file item from the display
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