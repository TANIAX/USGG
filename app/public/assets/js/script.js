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
 * @param {number} year - The year of the calendar.
 * @param {number} month - The month of the calendar (0-11, where 0 represents January).
 * @returns {Array<Array<Object>>} - The calendar as a 2D array of objects, where each object represents a day.
 */
function createCalendar(year, month, currentMonth = null) {
  //If current month is null, set it to the current month
  if (currentMonth == null) {
      currentMonth = new Date().getMonth();
  }

  let results = [];
  let today = new Date();
  let week = [];

  // find out first and last days of the month
  let firstDate = new Date(year, month, 1);
  let lastDate = new Date(year, month + 1, 0);

  // calculate first sunday and last saturday
  let firstSunday = getFirstSunday(firstDate);
  let lastSaturday = getLastSaturday(lastDate);

  // iterate days starting from first sunday
  let iterator = new Date(firstSunday);
  let i = 0;

  // ..until last saturday
  while (iterator <= lastSaturday) {
    if (i++ % 7 === 0) {
      // start new week when sunday
      week = [];
      results.push(week);
    }

    // push day to week
    week.push({
      date: new Date(iterator),
      before: iterator < firstDate, // add indicator if before current month
      after: iterator > lastDate, // add indicator if after current month
      isToday: iterator.toDateString() === today.toDateString(), // add indicator if current day
      isCurrentMonth: iterator.getMonth() === currentMonth, // add indicator if current month
      day: iterator.getDate(),
      dateString: iterator.toISOString(),
      isSelected: false,
    });
    // iterate to next day
    iterator.setDate(iterator.getDate() + 1);
  }

  
  return results;
}

/**
 * Returns the first Sunday before or on the given date.
 * @param {Date} firstDate - The date to find the first Sunday before or on.
 * @returns {Date} - The first Sunday before or on the given date.
 */
function getFirstSunday(firstDate) {
  let offset = firstDate.getDay();

  let result = new Date(firstDate);
  result.setDate(firstDate.getDate() - offset);

  return result;
}

/**
 * Returns the last Saturday before a given date.
 * @param {Date} lastDate - The date for which to find the last Saturday.
 * @returns {Date} - The last Saturday before the given date.
 */
function getLastSaturday(lastDate) {
  let offset = 6 - lastDate.getDay();

  let result = new Date(lastDate);
  result.setDate(lastDate.getDate() + offset);

  return result;
}

window.addEventListener("scroll", reveal);
reveal();



//When page is loaded 
document.addEventListener("DOMContentLoaded", function () {
  let magnets = document.querySelectorAll(".magnetic");
  console.log("magnets", magnets);
  magnets.forEach((magnet) => {
    console.log(magnet);
    magnet.addEventListener("mousemove", moveMagnet);
    magnet.addEventListener("mouseout", function (event) {
      TweenMax.to(event.currentTarget, 1, { x: 0, y: 0, ease: Power4.easeOut });
    });
  });
  
});
