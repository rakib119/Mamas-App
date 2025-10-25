@php
    use Carbon\Carbon;
@endphp
@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
    <style>
        #remainder-section{
            padding-top: 40px;
        }
        .alarm-list-container{
            max-height:450px !important;
            scrollbar-width: none; /* Firefox 64+ */
            -ms-overflow-style: none; /* IE and Edge */
            overflow-y: scroll;
        }
        .alarm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            color: var(--tertiary-color);
            background: var(--table-head-2);
        }
        .alarm-header h4 {
            color: var(--tertiary-color);
        }
        .alarm-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .alarm-item {
            background: var(--table-head-2);
            border-bottom: 1px solid #ddd;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .alarm-item:hover {
            background: var(--brand-color);
        }
        .alarm-time {
            font-size: 28px;
            font-weight: 600;
            color: var(--tertiary-color);
        }
        .alarm-label {
            font-size: 14px;
            color: var(--tertiary-color);
        }
        .btn-add {
            color: var(--tertiary-color);
            font-size: 28px;
            font-weight: bold;
            background: none;
            border: none;
        }
        .modal-header {
            justify-content: space-between;
            align-items: center;
            border-bottom: none;
        }
        .modal-title {
            font-weight: 600;
        }
        .btn-cancel, .btn-save {
            background: none;
            border: none;
            color: #00D094;
            font-size: 16px;
            font-weight: 600;
        }
        .delete-btn {
            color: red;
            font-weight: 600;
            margin-top: 20px;
            width: 100%;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #00D094;
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        .slider.round {
            border-radius: 34px;
        }

    </style>
@endsection
@section('content')
    <section id="remainder-section" class="feature feature--style1 padding-bottom padding-top-2 bg-color">
        <div class="section-header section-header--max50">
            <h6 class="mb-10 mt-minus-5 text-white">Daily <span>Routine</span></h6>
        </div>
        <div class="alarm-container">
            <div class="alarm-header" >
                <h4 class="mb-0">Routine</h4>
                <button class="btn-add" id="addAlarmBtn">+</button>
            </div>
            <div class="alarm-list-container">
                <ul class="alarm-list" id="alarmList"></ul>
            </div>
            <div class="modal fade" id="alarmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <button class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <h5 class="modal-title">Add Alarm</h5>
                        <button class="btn-save" id="saveAlarmBtn">Save</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Time</label>
                            <input type="time" class="form-control" id="alarmTime">
                        </div>
                        <div class="mb-3">
                            <label>Label</label>
                            <input type="text" class="form-control" id="alarmLabel" placeholder="Alarm label">
                        </div>
                        <div class="mb-3">
                            <label>Repeat Days</label><br>
                            <div id="dayButtons" class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Sun">Sun</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Mon">Mon</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Tue">Tue</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Wed">Wed</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Thu">Thu</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Fri">Fri</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm day-btn" data-day="Sat">Sat</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Reminder Before</label>
                        <div class="d-flex gap-2">
                        <input type="number" class="form-control" id="remHour" placeholder="Hour" min="0" style="max-width:100px;">
                        <input type="number" class="form-control" id="remMin" placeholder="Min" min="0" max="59" style="max-width:100px;">
                    </div>
                    </div>
                        <button class="btn btn-outline-danger delete-btn d-none" id="deleteAlarmBtn">Delete Alarm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
@section('javaScript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const alarmModal = new bootstrap.Modal(document.getElementById('alarmModal'));
        const addAlarmBtn = document.getElementById('addAlarmBtn');
        const saveAlarmBtn = document.getElementById('saveAlarmBtn');
        const deleteAlarmBtn = document.getElementById('deleteAlarmBtn');
        const alarmList = document.getElementById('alarmList');

        let alarms = [];
        let editIndex = null;
        let editId = null;

        // ✅ Load alarms from database
        async function fetchAlarms() {
            const res = await fetch("{{ route('routine.list') }}");
            alarms = await res.json();
            renderAlarms();
        }

        function formatTimeTo12Hour(timeStr) {
            if (!timeStr) return '';
            let [hour, minute] = timeStr.split(':').map(Number);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            hour = hour % 12;
            hour = hour ? hour : 12; // 0 becomes 12
            return `${hour}:${minute.toString().padStart(2, '0')} ${ampm}`;
        }
        function renderAlarms() {
            alarmList.innerHTML = '';
            alarms.forEach((alarm, index) => {
                const li = document.createElement('li');
                li.className = 'alarm-item';
                li.innerHTML = `
                    <div style="flex:1" class="alarm-info">
                        <div class="alarm-time">${formatTimeTo12Hour(alarm.time)}</div>
                        <div class="alarm-label">${alarm.label || ''} ${alarm.days ? '('+JSON.parse(alarm.days).join(', ')+')' : ''}</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" ${alarm.enabled ? 'checked' : ''} data-id="${alarm.id}">
                        <span class="slider round"></span>
                    </label>
                `;
                li.querySelector('.alarm-info').addEventListener('click', () => openEditModal(index));
                li.querySelector('input[type="checkbox"]').addEventListener('click', async (e) => {
                    e.stopPropagation(); // prevent opening popup
                    const enabled = e.target.checked ? 1 : 0;
                    await toggleAlarm(alarm.id, enabled);
                });
                alarmList.appendChild(li);
            });
        }


        function resetModal() {
            document.querySelector('.modal-title').textContent = 'Add Alarm';
            document.getElementById('alarmTime').value = '00:00';
            document.getElementById('alarmLabel').value = '';
            document.querySelectorAll('.day-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('remHour').value = '';
            document.getElementById('remMin').value = '';
            deleteAlarmBtn.classList.add('d-none');
            editIndex = null;
            editId = null;
        }

        addAlarmBtn.addEventListener('click', () => {
            resetModal();
            alarmModal.show();
        });

        saveAlarmBtn.addEventListener('click', async () => {
            const time = document.getElementById('alarmTime').value;
            const label = document.getElementById('alarmLabel').value;
            const days = [...document.querySelectorAll('.day-btn.active')].map(b => b.dataset.day);
            const remHour = document.getElementById('remHour').value;
            const remMin = document.getElementById('remMin').value;
            const token = '{{ csrf_token() }}';

            const alarmData = { time, label, days, remHour, remMin, enabled: 1 };

            if (editId) {
                await fetch(`/routine/${editId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(alarmData)
                });
            } else {
                await fetch(`/routine`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(alarmData)
                });
            }

            alarmModal.hide();
            fetchAlarms();
        });

        deleteAlarmBtn.addEventListener('click', async () => {
            if (editId) {
                await fetch(`/routine/${editId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                alarmModal.hide();
                fetchAlarms();
            }
        });

        document.querySelectorAll('.day-btn').forEach(btn => {
            btn.addEventListener('click', () => btn.classList.toggle('active'));
        });

        function openEditModal(index) {
            const alarm = alarms[index];
            editIndex = index;
            editId = alarm.id;

            document.querySelector('.modal-title').textContent = 'Edit Alarm';
            document.getElementById('alarmTime').value = alarm.time;
            document.getElementById('alarmLabel').value = alarm.label;
            document.getElementById('remHour').value = alarm.remHour;
            document.getElementById('remMin').value = alarm.remMin;
            document.querySelectorAll('.day-btn').forEach(b => {
                const days = alarm.days ? JSON.parse(alarm.days) : [];
                b.classList.toggle('active', days.includes(b.dataset.day));
            });
            deleteAlarmBtn.classList.remove('d-none');
            alarmModal.show();
        }
        async function toggleAlarm(id, enabled) {
            await fetch(`/routine/${id}/toggle`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ enabled })
            });
        }

        // Initial load
        fetchAlarms();
    </script>

@endsection
