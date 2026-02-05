<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Calendar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                events: '{{ route("admin.calendar.events") }}',
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    alert(
                        'Customer: ' + props.customer + '\n' +
                        'Address: ' + props.address + '\n' +
                        'Service: ' + props.service + '\n' +
                        'Tech: ' + props.tech + '\n' +
                        'Status: ' + props.status + '\n' +
                        'Price: ' + props.price
                    );
                },
                eventDidMount: function(info) {
                    info.el.title = info.event.extendedProps.customer + ' - ' + info.event.extendedProps.service;
                }
            });

            calendar.render();
        });
    </script>

    <style>
        .fc-event {
            cursor: pointer;
        }
    </style>
</x-app-layout>
