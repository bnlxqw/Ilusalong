document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.booking-calendar').forEach(function (calendar) {
    const form = calendar.closest('form');
    const specialistSelect = form.querySelector('#booking-specialist');
    const timeInput = form.querySelector('#booking-time');
    const dateInput = form.querySelector('#booking-date');
    const daysContainer = calendar.querySelector('[data-calendar-days]');
    const monthLabel = calendar.querySelector('[data-calendar-month]');
    const status = calendar.querySelector('[data-calendar-status]');
    const bookedTimesContainer = calendar.querySelector('[data-calendar-booked-times]');
    const bookedSlots = JSON.parse(calendar.dataset.bookedSlots || '{}');
    const locale = calendar.dataset.locale === 'ru' ? 'ru-RU' : 'en-GB';
    const minDate = calendar.dataset.minDate;
    const todayParts = minDate.split('-').map(Number);
    const today = new Date(todayParts[0], todayParts[1] - 1, todayParts[2]);
    const procedureDurationMinutes = 30;
    let displayedMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    let selectedDate = '';

    function toDateKey(year, month, day) {
      return year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
    }

    function bookedTimesFor(dateKey) {
      const specialistId = specialistSelect.value;
      return specialistId && bookedSlots[specialistId] && bookedSlots[specialistId][dateKey]
        ? bookedSlots[specialistId][dateKey]
        : [];
    }

    function overlapsProcedure(time, bookedTimes) {
      const parts = time.split(':').map(Number);
      const minutes = parts[0] * 60 + parts[1];
      return bookedTimes.some(function (bookedTime) {
        const bookedParts = bookedTime.split(':').map(Number);
        const bookedMinutes = bookedParts[0] * 60 + bookedParts[1];
        return Math.abs(minutes - bookedMinutes) < procedureDurationMinutes;
      });
    }

    function renderAvailableTimes() {
      timeInput.replaceChildren();
      const placeholder = document.createElement('option');
      placeholder.value = '';
      placeholder.textContent = selectedDate
        ? calendar.dataset.chooseTime
        : calendar.dataset.chooseDateFirst;
      timeInput.appendChild(placeholder);

      const canChooseTime = Boolean(specialistSelect.value && selectedDate);
      timeInput.disabled = !canChooseTime;
      if (!canChooseTime) return;

      const busyTimes = bookedTimesFor(selectedDate);
      for (let minute = 8 * 60; minute <= 19 * 60; minute += 1) {
        const time = String(Math.floor(minute / 60)).padStart(2, '0') + ':' +
          String(minute % 60).padStart(2, '0');
        const option = document.createElement('option');
        option.value = time;
        option.textContent = time;
        const isPast = new Date(selectedDate + 'T' + time) <= new Date();
        if (isPast) continue;
        if (busyTimes.indexOf(time) !== -1) {
          option.disabled = true;
          option.textContent += ' — ' + calendar.dataset.timeBookedLabel;
          timeInput.appendChild(option);
          continue;
        }
        if (overlapsProcedure(time, busyTimes)) continue;
        timeInput.appendChild(option);
      }
    }

    function renderSelectedDate() {
      const specialistId = specialistSelect.value;
      bookedTimesContainer.replaceChildren();
      status.classList.remove('is-booked', 'is-unavailable', 'is-available');

      if (!specialistId) {
        status.textContent = calendar.dataset.chooseSpecialist;
        return;
      }
      if (!selectedDate) {
        status.textContent = calendar.dataset.chooseDate;
        return;
      }

      const dateParts = selectedDate.split('-').map(Number);
      const dateLabel = new Date(dateParts[0], dateParts[1] - 1, dateParts[2])
        .toLocaleDateString(locale, { day: 'numeric', month: 'long', year: 'numeric' });
      const busyTimes = bookedTimesFor(selectedDate);

      if (busyTimes.length === 0) {
        status.textContent = dateLabel + ' — ' + calendar.dataset.noBookedTimes;
      } else {
        status.textContent = dateLabel + ' — ' + calendar.dataset.bookedLabel;
        busyTimes.forEach(function (time) {
          const chip = document.createElement('span');
          chip.className = 'calendar-booked-time';
          chip.setAttribute('aria-disabled', 'true');
          chip.textContent = time;
          bookedTimesContainer.appendChild(chip);
        });
      }
      updateBookingDate();
    }

    function renderCalendar() {
      const year = displayedMonth.getFullYear();
      const month = displayedMonth.getMonth();
      monthLabel.textContent = new Intl.DateTimeFormat(locale, {
        month: 'long',
        year: 'numeric'
      }).format(displayedMonth);
      daysContainer.replaceChildren();

      const firstWeekday = (new Date(year, month, 1).getDay() + 6) % 7;
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      for (let blank = 0; blank < firstWeekday; blank += 1) {
        const emptyCell = document.createElement('span');
        emptyCell.className = 'calendar-day-empty';
        emptyCell.setAttribute('aria-hidden', 'true');
        daysContainer.appendChild(emptyCell);
      }

      for (let day = 1; day <= daysInMonth; day += 1) {
        const dateKey = toDateKey(year, month, day);
        const busyTimes = bookedTimesFor(dateKey);
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'calendar-day';
        button.textContent = String(day);
        button.disabled = dateKey < minDate || !specialistSelect.value;
        button.setAttribute('aria-pressed', dateKey === selectedDate ? 'true' : 'false');
        button.setAttribute('aria-label', new Date(year, month, day)
          .toLocaleDateString(locale, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }));

        if (busyTimes.length > 0) {
          button.classList.add('has-bookings');
          button.title = busyTimes.length + ' ' + calendar.dataset.bookedLabel + ' ' + busyTimes.join(', ');
          const marker = document.createElement('span');
          marker.className = 'calendar-day-marker';
          marker.setAttribute('aria-hidden', 'true');
          button.appendChild(marker);
        }
        if (dateKey === selectedDate) button.classList.add('is-selected');

        button.addEventListener('click', function () {
          selectedDate = dateKey;
          timeInput.value = '';
          dateInput.value = '';
          timeInput.setCustomValidity('');
          renderAvailableTimes();
          renderCalendar();
          renderSelectedDate();
        });
        daysContainer.appendChild(button);
      }
    }

    function updateBookingDate() {
      const busyTimes = bookedTimesFor(selectedDate);
      const hasSelection = selectedDate && timeInput.value;
      dateInput.value = hasSelection ? selectedDate + 'T' + timeInput.value : '';
      const isBooked = hasSelection && busyTimes.indexOf(timeInput.value) !== -1;
      if (isBooked) {
        timeInput.value = '';
        dateInput.value = '';
        timeInput.setCustomValidity(calendar.dataset.timeTaken);
        status.textContent = calendar.dataset.timeTaken;
        status.classList.add('is-booked');
        status.classList.remove('is-unavailable', 'is-available');
        return;
      }

      const isPast = hasSelection && new Date(dateInput.value) <= new Date();
      timeInput.setCustomValidity(
        isPast ? calendar.dataset.timePast : ''
      );
      if (hasSelection) {
        status.textContent = isPast ? calendar.dataset.timePast : calendar.dataset.timeAvailable;
        status.classList.remove('is-booked');
        status.classList.toggle('is-unavailable', isPast && !isBooked);
        status.classList.toggle('is-available', !isPast);
      }
    }

    specialistSelect.addEventListener('change', function () {
      selectedDate = '';
      timeInput.value = '';
      dateInput.value = '';
      timeInput.setCustomValidity('');
      renderAvailableTimes();
      renderCalendar();
      renderSelectedDate();
    });

    timeInput.addEventListener('input', updateBookingDate);
    timeInput.addEventListener('change', updateBookingDate);
    calendar.querySelector('[data-calendar-prev]').addEventListener('click', function () {
      displayedMonth = new Date(displayedMonth.getFullYear(), displayedMonth.getMonth() - 1, 1);
      renderAvailableTimes();
      renderCalendar();
    });
    calendar.querySelector('[data-calendar-next]').addEventListener('click', function () {
      displayedMonth = new Date(displayedMonth.getFullYear(), displayedMonth.getMonth() + 1, 1);
      renderCalendar();
    });
    form.addEventListener('submit', function (event) {
      updateBookingDate();
      if (!selectedDate) {
        event.preventDefault();
        status.textContent = calendar.dataset.chooseDate;
        calendar.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else if (!dateInput.value || timeInput.validity.customError) {
        event.preventDefault();
        calendar.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

    renderCalendar();
    renderSelectedDate();
  });
});
