document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.getElementById('calendar');
    const timeSelect = document.getElementById('time-select');
    const terminInput = document.getElementById('termin');
    const terminForm = document.getElementById('termin-form');
    const monthYear = document.getElementById('month-year');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');

    const holidays = {
        2024: [
            '2024-01-01', // Neujahr
            '2024-04-19', // Karfreitag
            '2024-04-21', // Ostersonntag
            '2024-04-22', // Ostermontag
            '2024-05-01', // Tag der Arbeit
            '2024-05-09', // Christi Himmelfahrt
            '2024-05-19', // Pfingstsonntag
            '2024-05-20', // Pfingstmontag
            '2024-10-03', // Tag der Deutschen Einheit
            '2024-10-31', // Reformationstag
            '2024-12-25', // Weihnachten
            '2024-12-26', // 2. Weihnachtstag
        ],
        2025: [
            '2025-01-01', // Neujahr
            '2025-04-04', // Karfreitag
            '2025-04-06', // Ostersonntag
            '2025-04-07', // Ostermontag
            '2025-05-01', // Tag der Arbeit
            '2025-05-29', // Christi Himmelfahrt
            '2025-06-08', // Pfingstsonntag
            '2025-06-09', // Pfingstmontag
            '2025-10-03', // Tag der Deutschen Einheit
            '2025-10-31', // Reformationstag
            '2025-12-25', // Weihnachten
            '2025-12-26', // 2. Weihnachtstag
        ]
    };

    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    const updateCalendar = () => {
        calendar.innerHTML = '';
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

        monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;

        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            calendar.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentYear, currentMonth, day);
            const dateString = date.toISOString().split('T')[0];
            const dayElement = document.createElement('div');
            dayElement.textContent = day;

            if (date.getDay() === 5 && !holidays[currentYear]?.includes(dateString)) { // Friday and not a holiday
                dayElement.classList.add('available');
                dayElement.addEventListener('click', () => {
                    document.querySelectorAll('.calendar div').forEach(el => el.classList.remove('selected'));
                    dayElement.classList.add('selected');
                    timeSelect.style.display = 'block';
                    timeSelect.addEventListener('change', () => {
                        const selectedDateTime = dateString + 'T' + timeSelect.value;
                        fetch('check_appointment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'termin=' + selectedDateTime
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data === 'available') {
                                    terminInput.value = selectedDateTime;
                                    terminForm.querySelector('button[type="submit"]').disabled = false;
                                } else {
                                    alert('Der ausgewählte Termin ist bereits vergeben.');
                                    terminForm.querySelector('button[type="submit"]').disabled = true;
                                }
                            });
                    });
                });
            } else {
                dayElement.classList.add('unavailable');
            }

            calendar.appendChild(dayElement);
        }
    };

    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendar();
    });

    updateCalendar();
});