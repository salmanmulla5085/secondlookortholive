@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

    
    <style>
    
    .fc-event
    {
        border-color: rgb(9, 173, 223) !important; 
        background-color: rgb(9, 173, 223)  !important; 
        color:white;
    }

    .fc .fc-more-popover .fc-popover-body {
min-width: 220px;
padding: 10px;
overflow: hidden !important;
overflow-y: scroll !important;
min-height: 180px;
max-height: 180px !important;
}
    
    .fc-daygrid-dot-event:hover
    {
        background-color: #fff !important;  
        color:#000 !important; 
    }
    
    </style>

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

                                        @if (session('danger'))
                                            <div class="alert alert-danger">
                                                {{ session('danger') }}
                                            </div>
                                        @endif
                                        
    </div>        
                             


    <div class="row mt-4">        
        <div class="col-12">
            <!-- <div class="alert alert-light" role="alert">
                <strong>
                </strong>
            </div> -->
            <div class="row">            

                    <div class="col-md-6">
                        
                    
                    </div>
            </div>
            <?php
                $sql = "SELECT * FROM dbl_users where user_type = 'doctor'";
                $users = DB::select($sql);
                $doctors = collect($users); 
            ?>
            <div class="box-main p-3 bg-white margin-15-b radius8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Doctor's Availability :: 
                    <!--<label for="docto">{{ __('Select Doctor') }}</label>-->
                    <!--                <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" autocomplete="doctor" style="display: inline;width:20%">-->
                    <!--                    <option value="" selected >@lang('Choose')...</option>-->
                    <!--                    @foreach($doctors as $doctor)-->
                    <!--                        <option value="{{ old('doctor', $doctor->id) }}">{{ old('doctor', $doctor->first_name) }}</option>-->
                    <!--                    @endforeach-->
                    <!--</select>-->
                    <a href="{{ URL('Doctor-add-AvailableScheduleSlots') }}" class="btn btn-orange pl-2" style="margin-left:20px">{{__('Add Time Slot')}}</a>
                    </h6>
                    
                    
                    
                </div>
                
                <div class="card-body px-4 pt-4 pb-4">

                    <div id="calendar" style ="width:100%; height:100vh">
                        
                    </div>
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

    <script type="text/javascript">
       
        var calendarEl = document.getElementById('calendar');
        var events = [];
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            events: '{{ url("/") }}/Doctor-AvailableScheduleSlots_events',
            editable: true,
            dayMaxEvents: 4, // Show only 1 event, others will be collapsed into "+ more"
            // Deleting The Event
            eventContent: function(info) {
                var eventTitle = info.event.title;

                var parts = eventTitle.split(" To ");

                // Function to remove seconds from a time string
                function removeSeconds(time) {
                    return time.substring(0, time.lastIndexOf(':'));
                }

                var RemoveSecInTitle = removeSeconds(parts[0]) + " To " + removeSeconds(parts[1]);

                updatedTitle = "Dr." + RemoveSecInTitle;

                var eventElement = document.createElement('div');
                
                eventElement.innerHTML = '<span style="cursor: pointer;">❌</span>' + updatedTitle;
                
                eventElement.querySelector('span').addEventListener('click', function() {
                    if (confirm("Are you sure you want to delete this event?")) {
                        var eventId = info.event.id;
                        $.ajax({
                            method: 'get',
                            url: '{{ url("/") }}/Doctor-AvailableScheduleSlots/delete/' + eventId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log('Event deleted successfully.');
                                calendar.refetchEvents(); // Refresh events after deletion
                            },
                            error: function(error) {
                                console.error('Error deleting event:', error);
                            }
                        });
                    }
                });
                return {
                    domNodes: [eventElement]
                };
            },

            eventDidMount: function(info) {
                        // Add a click event to the "+ more" link
                        if (info.el.querySelector('.fc-more')) {
                        info.el.querySelector('.fc-more').addEventListener('click', function(e) {
                            e.preventDefault();
                            showMoreEventsModal(info.event.start);
                        });
                        }
            },
            // Drag And Drop

            eventDrop: function(info) {
                var eventId = info.event.id;
                var newStartDate = info.event.start;
                var newEndDate = info.event.end || newStartDate;
                var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
                var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                $.ajax({
                    method: 'post',
                    url: `/schedule/${eventId}`,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        start_date: newStartDateUTC,
                        end_date: newEndDateUTC,
                    },
                    success: function() {
                        console.log('Event moved successfully.');
                    },
                    error: function(error) {
                        console.error('Error moving event:', error);
                    }
                });
            },

            // Event Resizing
            eventResize: function(info) {
                var eventId = info.event.id;
                var newEndDate = info.event.end;
                var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                $.ajax({
                    method: 'post',
                    url: `/Doctor-AvailableScheduleSlots/${eventId}/resize`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        end_date: newEndDateUTC
                    },
                    success: function() {
                        console.log('Event resized successfully.');
                    },
                    error: function(error) {
                        console.error('Error resizing event:', error);
                    }
                });
            },
        });

        calendar.render();

        function showMoreEventsModal(date) {
                // Get all events for the selected date
                const events = calendar.getEvents().filter(event => 
                event.start.toISOString().split('T')[0] === date.toISOString().split('T')[0]
                );

                // Create content for the modal
                let modalContent = events.map(event => `
                <p><b>${event.title}</b> ${event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                </p>
                `).join('');

                // <i>${event.extendedProps.type}</i>

                // Display modal (replace with a custom modal in a real application)
                alert(modalContent); // Replace with modal logic
        }


        function filterAndDisplayEvents(searchKeywords) {
            $.ajax({
                method: 'GET',
                url: `{{ url('/') }}/Doctor-AvailableScheduleSlots/search?title=${searchKeywords}`,
                success: function(response) {
                    calendar.removeAllEvents();
                    calendar.addEventSource(response);
                },
                error: function(error) {
                    console.error('Error searching events:', error);
                }
            });
        }


       
    </script>
@endsection