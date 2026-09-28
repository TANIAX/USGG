/**
 * Reveals elements with the "reveal" class when they are scrolled into view.
 * @function
 * @returns {void}
 */
function reveal() {
  let reveals = document.querySelectorAll(
    ".reveal , .reveal-left , .reveal-right , .reveal-reverse"
  );
  for (let i = 0; i < reveals.length; i++) {
    let windowHeight = window.innerHeight;
    let elementTop = reveals[i].getBoundingClientRect().top;
    let elementVisible = 150;
    if (elementTop < windowHeight - elementVisible) {
      reveals[i].classList.add("active");
    } else {
      reveals[i].classList.remove("active");
    }
  }
}

/**
 * Moves the magnet button based on the mouse position.
 * @param {Event} event - The mouse event object.
 */
function moveMagnet(event) {
  let magnetButton = event.currentTarget;
  let bounding = magnetButton.getBoundingClientRect();
  let strength = 50;
  TweenMax.to(magnetButton, 1, {
    x:
      ((event.clientX - bounding.left) / magnetButton.offsetWidth - 0.5) *
      strength,
    y:
      ((event.clientY - bounding.top) / magnetButton.offsetHeight - 0.5) *
      strength,
    ease: Power4.easeOut,
  });
}

/**
 * Creates a calendar for the specified year and month.
 * Weeks start on Monday and every date is handled in local time (no UTC conversion),
 * so the `key` of a day (YYYY-MM-DD) always matches the day displayed.
 * @param {number} year - The year of the calendar.
 * @param {number} month - The month of the calendar (0-11, where 0 represents January).
 * @returns {Array<Array<Object>>} - The calendar as a 2D array of objects, where each object represents a day.
 */
function createCalendar(year, month) {
  let results = [];
  let todayKey = toDateKey(new Date());
  let week = [];

  // find out first and last days of the month
  let firstDate = new Date(year, month, 1);
  let lastDate = new Date(year, month + 1, 0);

  // calculate first monday and last sunday
  let firstMonday = getFirstMonday(firstDate);
  let lastSunday = getLastSunday(lastDate);

  // iterate days starting from first monday
  let iterator = new Date(firstMonday);
  let i = 0;

  // ..until last sunday
  while (iterator <= lastSunday) {
    if (i++ % 7 === 0) {
      // start new week when monday
      week = [];
      results.push(week);
    }

    let key = toDateKey(iterator);

    // push day to week
    week.push({
      date: new Date(iterator),
      key: key, // local date as YYYY-MM-DD
      isToday: key === todayKey, // add indicator if current day
      isCurrentMonth: iterator.getMonth() === month, // add indicator if current month
      day: iterator.getDate(),
    });
    // iterate to next day
    iterator.setDate(iterator.getDate() + 1);
  }

  return results;
}

/**
 * Returns the local date as a YYYY-MM-DD string (unlike toISOString() which converts to UTC and can shift the day).
 * @param {Date} date - The date to format.
 * @returns {string} - The date formatted as YYYY-MM-DD.
 */
function toDateKey(date) {
  let month = String(date.getMonth() + 1).padStart(2, "0");
  let day = String(date.getDate()).padStart(2, "0");

  return date.getFullYear() + "-" + month + "-" + day;
}

/**
 * Returns the first Monday before or on the given date.
 * @param {Date} firstDate - The date to find the first Monday before or on.
 * @returns {Date} - The first Monday before or on the given date.
 */
function getFirstMonday(firstDate) {
  let offset = (firstDate.getDay() + 6) % 7;

  let result = new Date(firstDate);
  result.setDate(firstDate.getDate() - offset);

  return result;
}

/**
 * Returns the last Sunday after or on the given date.
 * @param {Date} lastDate - The date for which to find the last Sunday.
 * @returns {Date} - The last Sunday after or on the given date.
 */
function getLastSunday(lastDate) {
  let offset = (7 - lastDate.getDay()) % 7;

  let result = new Date(lastDate);
  result.setDate(lastDate.getDate() + offset);

  return result;
}

window.addEventListener("scroll", reveal);
reveal();



//When page is loaded 
document.addEventListener("DOMContentLoaded", function () {
  let magnets = document.querySelectorAll(".magnetic");
  magnets.forEach((magnet) => {
    magnet.addEventListener("mousemove", moveMagnet);
    magnet.addEventListener("mouseout", function (event) {
      TweenMax.to(event.currentTarget, 1, { x: 0, y: 0, ease: Power4.easeOut });
    });
  });
  
});
