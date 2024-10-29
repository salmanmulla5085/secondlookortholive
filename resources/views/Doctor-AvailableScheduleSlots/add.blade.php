@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

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

    <?php
    $sql = "SELECT * FROM dbl_users where user_type = 'doctor'";
    $users = DB::select($sql);
    $doctors = collect($users);                                 



    ?>
    <div class="row mt-4">
    <div class="col-12">
        <div class="box-main p-3 bg-white margin-15-b radius8">
            <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Add Available Time Slot</h6>
                    </div>
                    <div class="card-body px-4 pt-4 pb-4">
                        
                        <form action="{{ URL('/Doctor-create-AvailableScheduleSlots') }}" method="POST">
                            @csrf                         
                            <br>
                            <label>For one day schdeule select same date in "End Date"</label><br>
                            
                                <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start">{{__('Start Date')}}</label>
                                    <input type='text' class='form-control' id='start_date_picker' name='start' value="{{ \Carbon\Carbon::now()->format('m-d-Y') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end">{{__('End Date')}}</label>
                                    <input type='text' class='form-control' id='end_date_picker' name='end' value="{{ \Carbon\Carbon::now()->format('m-d-Y') }}" required>
                                </div>
                            </div>

                            <!-- <label for="color">{{__('Color')}}</label>
                            <input type="color" id="color" name="color" /> -->

                            <!--<label>Repeat this timeslot for following days for 1 month:</label><br>-->
                            <!--<p>Note: you can delete these timeslots from calendar.</p>-->
                            
                            <!--
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="monday" name="days[]" value="Monday">
                                <label class="form-check-label" for="monday">Monday</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="tuesday" name="days[]" value="Tuesday">
                                <label class="form-check-label" for="tuesday">Tuesday</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="wednesday" name="days[]" value="Wednesday">
                                <label class="form-check-label" for="wednesday">Wednesday</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="thursday" name="days[]" value="Thursday">
                                <label class="form-check-label" for="thursday">Thursday</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="friday" name="days[]" value="Friday">
                                <label class="form-check-label" for="friday">Friday</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="saturday" name="days[]" value="Saturday">
                                <label class="form-check-label" for="saturday">Saturday</label>
                            </div>
                            <br>
                            -->
                            
                            
                                <label>Select time slots:</label>
                                <div class="time-slots">
                                    <!-- Time slots will be dynamically inserted here -->
                                </div>
                                
                            <input type="submit" value="Save" class="btn btn-orange" />
                        </form>
                            
                    </div>
            </div>
        </div>
    </div>
    </div>
  
    
</main>
</div>

<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>    
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
</body>
</html>


<script>
      $(document).ready(function () {
        $(function () {
            $("#start_date_picker").datepicker({
                dateFormat: 'mm-dd-yy'
            });
            $("#end_date_picker").datepicker({
                dateFormat: 'mm-dd-yy'
            });
        });
    }) 
    
    
    document.addEventListener('DOMContentLoaded', function () {
        const timeSlotsContainer = document.querySelector('.time-slots');
        const startHour = 6; // 06:00 AM
        const endHour = 23; // 11:00 PM
        const slotDuration = 30; // in minutes

        let html = '<div class="row">';
        let count = 0;
        
        for (let hour = startHour; hour <= endHour; hour++) {
            for (let minute = 0; minute < 60; minute += slotDuration) {
                let startTime = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                let endHourSlot = minute + slotDuration === 60 ? hour + 1 : hour;
                let endTime = `${String(endHourSlot).padStart(2, '0')}:${String((minute + slotDuration) % 60).padStart(2, '0')}`;
                let id = `slot-${startTime.replace(':', '-')}-${endTime.replace(':', '-')}`;
                let value = `${startTime}-${endTime}`;
                let label = `${startTime} To ${endTime}`;

                html += `
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="${id}" name="slots[]" value="${value}">
                            <label class="form-check-label" for="${id}">${label}</label>
                        </div>
                    </div>
                `;

                count++;
                
                // Add a new row after every 4 checkboxes
                if (count % 4 === 0) {
                    html += '</div><div class="row">';
                }
            }
        }
        
        // Close the last row div
        html += '</div>';
        
        timeSlotsContainer.innerHTML = html;
    });
    </script>    
@endsection