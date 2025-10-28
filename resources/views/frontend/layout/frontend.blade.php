<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Baby Kick Counter</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/all.min.css')}}" />
  @yield('css')
</head>
<body>

    @yield('content')
    {{-- <div class="modal fade" id="alarmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Do you complete this tak?</h5>
                    <button class="btn-cancel" id="btn-cancel">Cancel</button>
                </div>
                <div class="modal-body">


                    <button class="btn btn-outline-danger delete-btn" id="deleteAlarmBtn">Delete Alarm</button>
                </div>
            </div>
        </div>
    </div> --}}
    <section id="android-menu">
        <div class="menu-wrapper">
            <div class="android-menu-bar">
                <a class="android-menu-item nav-link active" href="{{ route('home')}}">
                    <div class="item-inner active">
                        <i class="fa-solid fa-home"></i>
                        <p>Home</p>
                    </div>
                </a>
                <a class="android-menu-item nav-link " href="{{ route('routine.index')}}">
                    <div class="item-inner">
                        <i class="fa-solid fa-utensils"></i>
                        <p>Routine</p>
                    </div>
                </a>
                <a class="android-menu-item nav-link" href="{{ route('kick.history')}}">
                    <div class="item-inner">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <p>History</p>
                    </div>
                </a>
                <a class="android-menu-item nav-link " href="{{ route('profile')}}">
                    <div class="item-inner">
                        <i class="fa-solid fa-user"></i>
                        <p>Profile</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets/js/all.min.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
    {{-- ROUTINE STATUS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let alarms = [];
        let snoozedAlarms = {}; // { alarm_id: nextReminderTimestamp }
        let isModalOpen = false; // prevent multiple modals

        // ✅ Fetch alarms from backend
        async function fetchAlarms() {
            const res = await fetch("{{ route('routine.list') }}");
            alarms = await res.json();
        }

        // ✅ Convert time to 12-hour format
        function formatTime12(time) {
            const [h, m] = time.split(':').map(Number);
            const suffix = h >= 12 ? 'PM' : 'AM';
            const hour = ((h + 11) % 12) + 1;
            return `${hour}:${m.toString().padStart(2, '0')} ${suffix}`;
        }

        // ✅ Check alarms and show modal if within ±1 hour
        async function checkAlarms() {
            // 🧠 Skip if a modal is already open
            if (isModalOpen) return;

            const now = new Date();

            for (const alarm of alarms) {
                if (!alarm.enabled) continue;

                // Skip snoozed alarms
                if (snoozedAlarms[alarm.id] && now < snoozedAlarms[alarm.id]) {
                    continue;
                }

                const [alarmHour, alarmMin] = alarm.time.split(':').map(Number);
                const alarmTime = new Date();
                alarmTime.setHours(alarmHour, alarmMin, 0, 0);

                const diffMinutes = Math.round((now - alarmTime) / 60000);

                // Within ±1 hour
                if (Math.abs(diffMinutes) <= 60) {
                    const res = await fetch(`/routine/check-status/${alarm.id}`);
                    const data = await res.json();

                    if (!data.completed) {
                        showAlarmAlert(alarm);
                        break; // Show only one popup at a time
                    }
                }
            }
        }

        // ✅ Show Bootstrap modal reminder
        function showAlarmAlert(alarm) {
            if (isModalOpen) return; // safety double-check
            isModalOpen = true;

            const existing = document.getElementById('alertModal');
            if (existing) existing.remove();

            const modalHtml = `
                <div class="modal fade" id="alertModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4 shadow-lg" style="border-radius: 20px;">
                            <h4 class="mb-3 text-success fw-bold">⏰ Alarm Reminder</h4>
                            <p class="mb-4">
                                It's time for <strong>${alarm.label || 'your scheduled activity'}</strong>.<br>
                                (${formatTime12(alarm.time)})
                            </p>
                            <div class="d-flex justify-content-center gap-3 mt-3">
                                <button class="btn btn-outline-secondary px-4" id="cancelAlarmBtn">Close</button>
                                <button class="btn btn-success px-4" id="confirmAlarmBtn">Done</button>
                            </div>
                        </div>
                    </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const alertModalEl = document.getElementById('alertModal');
            const alertModal = new bootstrap.Modal(alertModalEl);
            alertModal.show();

            // ✅ Mark completed
            document.getElementById('confirmAlarmBtn').addEventListener('click', async () => {
                await fetch(`/routine/mark-completed`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ alarm_id: alarm.id })
                });
                alertModal.hide();
                alertModalEl.remove();
                isModalOpen = false;
            });

            // ✅ Snooze for 10 minutes
            document.getElementById('cancelAlarmBtn').addEventListener('click', () => {
                const snoozeUntil = new Date();
                snoozeUntil.setMinutes(snoozeUntil.getMinutes() + 10);
                snoozedAlarms[alarm.id] = snoozeUntil;
                alertModal.hide();
                alertModalEl.remove();
                isModalOpen = false;
            });

            // ✅ Reset flag when closed by any means
            alertModalEl.addEventListener('hidden.bs.modal', () => {
                isModalOpen = false;
            });
        }

        // ✅ Initialize
        document.addEventListener("DOMContentLoaded", async () => {
            await fetchAlarms();
            checkAlarms(); // check immediately
            setInterval(checkAlarms, 300000); // check every 5 minutes
        });
    </script>

    @yield('javaScript')

</body>
</html>
