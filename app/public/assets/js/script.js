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

/**
 * Parses a date coming from the database ("YYYY-MM-DD HH:MM:SS") as a local date.
 * @param {string} value - The date to parse.
 * @returns {Date} - The parsed date.
 */
function parseEventDate(value) {
  let [date, time = "00:00:00"] = value.split(" ");
  let [year, month, day] = date.split("-").map(Number);
  let [hours, minutes] = time.split(":").map(Number);

  return new Date(year, month - 1, day, hours, minutes);
}

/**
 * Returns a human readable (french) period for an agenda event.
 * e.g. "Samedi 3 octobre 2026, de 14:00 à 17:00" or "Du samedi 3 octobre au dimanche 4 octobre 2026"
 * @param {Object} event - The event ({start_at, end_at, all_day}).
 * @returns {string} - The formatted period.
 */
function formatEventPeriod(event) {
  let start = parseEventDate(event.start_at);
  let end = parseEventDate(event.end_at);
  let sameDay = toDateKey(start) === toDateKey(end);
  let sameYear = start.getFullYear() === end.getFullYear();
  let capitalize = (text) => text.charAt(0).toUpperCase() + text.slice(1);
  let day = (date, withYear) =>
    date.toLocaleDateString("fr-BE", { weekday: "long", day: "numeric", month: "long", year: withYear ? "numeric" : undefined });
  let time = (date) => date.toLocaleTimeString("fr-BE", { hour: "2-digit", minute: "2-digit" });

  if (sameDay && event.all_day) return capitalize(day(start, true)) + " (toute la journée)";
  if (sameDay && start.getTime() === end.getTime()) return capitalize(day(start, true)) + " à " + time(start);
  if (sameDay) return capitalize(day(start, true)) + ", de " + time(start) + " à " + time(end);
  if (event.all_day) return "Du " + day(start, !sameYear) + " au " + day(end, true);

  return "Du " + day(start, !sameYear) + " à " + time(start) + " au " + day(end, true) + " à " + time(end);
}

/**
 * Formats a date coming from the database in french.
 * @param {string} value - The date ("YYYY-MM-DD" or "YYYY-MM-DD HH:MM:SS").
 * @param {string} style - "long" (12 août 2026) or "short" (12 août 2026 with an abbreviated month).
 * @returns {string} - The formatted date, "" without date.
 */
function formatDate(value, style = "long") {
  if (!value) return "";
  let month = style === "short" ? "short" : "long";
  return parseEventDate(value).toLocaleDateString("fr-BE", { day: "numeric", month: month, year: "numeric" });
}

/**
 * Formats a size of file in french ("1,2 Mo", "350 Ko").
 * @param {number} bytes - The size.
 * @returns {string} - The formatted size.
 */
function formatFileSize(bytes) {
  return bytes > 1048576 ? (bytes / 1048576).toFixed(1).replace(".", ",") + " Mo" : Math.max(1, Math.round(bytes / 1024)) + " Ko";
}

/**
 * Calls an url of the site and returns its JSON answer ({success: true, ...}).
 * With "data", the request is a POST of a form (the arrays are sent as "name[]").
 * @param {string} url - The url.
 * @param {Object|FormData|null} data - The data to post, null for a GET.
 * @returns {Promise<Object>} - The JSON answer.
 * @throws {Error} - When the request failed, with the message of the server if there is one.
 */
async function requestJson(url, data = null) {
  let options = { headers: { "X-Requested-With": "XMLHttpRequest", Accept: "application/json" } };
  if (data !== null) {
    let body = data instanceof FormData ? data : new FormData();
    if (!(data instanceof FormData))
      Object.entries(data).forEach(([name, value]) =>
        Array.isArray(value) ? value.forEach((item) => body.append(name + "[]", item)) : body.append(name, value)
      );
    options = { ...options, method: "POST", body: body };
  }

  let response = await fetch(url, options);
  let json = null;
  try {
    json = await response.json();
  } catch (error) {
    // Not a JSON answer (error page, session expired...)
  }
  if (!response.ok || !json || !json.success) {
    let message = json && (json.message || json.messages);
    throw new Error(typeof message === "string" ? message : "");
  }
  return json;
}

/**
 * Alpine component: automatic carousel (crossfade) of the photos of an album cover.
 * - a photo is only loaded just before being displayed,
 * - the animation stops when the cover is not on screen or the tab is hidden,
 * - nothing moves if the user asked the system to reduce the animations.
 * @param {Array<number>} ids - The ids of the photos of the cover.
 * @param {number} delay - The time (ms) each photo is displayed.
 * @returns {Object} - The Alpine component.
 */
function coverCarousel(ids, delay = 4000) {
  return {
    ids: ids,
    current: 0,
    loaded: [0],
    timer: null,
    onScreen: false,

    init() {
      if (this.ids.length < 2 || window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

      new IntersectionObserver((entries) => {
        this.onScreen = entries[0].isIntersecting;
        this.onScreen ? this.start() : this.stop();
      }).observe(this.$el);

      document.addEventListener("visibilitychange", () => {
        document.hidden ? this.stop() : this.onScreen && this.start();
      });
    },

    start() {
      if (this.timer) return;
      this.load(this.current + 1);
      // Random extra time so that the covers of the page do not all change together
      this.timer = setTimeout(() => {
        this.timer = null;
        this.current = (this.current + 1) % this.ids.length;
        this.start();
      }, delay + Math.random() * 2000);
    },

    stop() {
      clearTimeout(this.timer);
      this.timer = null;
    },

    load(index) {
      index = index % this.ids.length;
      if (!this.loaded.includes(index)) this.loaded.push(index);
    },

    url(id) {
      return "/galerie/photo/" + id + "/miniature";
    },
  };
}

/**
 * Returns a JPEG version of a photo, turned upright and reduced to maxSize px, to be sent to the server
 * (faster on mobile and below the upload limit of the server).
 * If the browser can not read the image (e.g. some HEIC files), the original file is returned and processed by the server.
 * @param {File} file - The photo chosen by the user.
 * @param {number} maxSize - The maximum width / height.
 * @returns {Promise<Blob|File>} - The reduced photo.
 */
async function reducePhoto(file, maxSize = 2000) {
  try {
    const bitmap = await createImageBitmap(file, { imageOrientation: "from-image" });
    const ratio = Math.min(1, maxSize / Math.max(bitmap.width, bitmap.height));
    const canvas = document.createElement("canvas");
    canvas.width = Math.round(bitmap.width * ratio);
    canvas.height = Math.round(bitmap.height * ratio);
    const context = canvas.getContext("2d");
    context.fillStyle = "#fff";
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);
    bitmap.close();
    const blob = await new Promise((resolve) => canvas.toBlob(resolve, "image/jpeg", 0.9));
    return blob || file;
  } catch (error) {
    return file;
  }
}

/**
 * Replaces the file chosen in an <input type="file"> by its reduced version (see reducePhoto()).
 * @param {HTMLInputElement} input - The file input.
 * @param {number} maxSize - The maximum width / height.
 * @returns {Promise<File|null>} - The file now in the input.
 */
async function reduceInputPhoto(input, maxSize = 2000) {
  const file = input.files[0];
  if (!file) return null;

  const reduced = await reducePhoto(file, maxSize);
  if (reduced === file || typeof DataTransfer === "undefined") return file;

  const reducedFile = new File([reduced], file.name.replace(/\.[^.]+$/, "") + ".jpg", { type: "image/jpeg" });
  const transfer = new DataTransfer();
  transfer.items.add(reducedFile);
  input.files = transfer.files;
  return reducedFile;
}

/**
 * Alpine component of a paginated list of the administration (filters, search and pages done by the server).
 * The page of the server (listResponse() of BaseController) gives the first result; the next ones are asked
 * in JSON (?format=json) when the page, the number per page, a filter or the search change. The url is kept
 * up to date, so that a reload or a link shows the same page.
 * @param {string} url - The url of the list (e.g. "/admin/inscriptions").
 * @param {Object} list - The first result: {items, pagination, filters, counts}.
 * @param {Object} extra - The own properties and methods of the page (getters kept). "setup()" is called by init().
 * @returns {Object} - The Alpine component: items, pagination, counts, one property per filter ("search"...).
 */
function listApp(url, list, extra = {}) {
  // Values not written in the url (the page may be opened with other values, e.g. ?status=new)
  const defaults = list.defaults || {};
  const defaultPerPage = defaults.per_page || list.pagination.per_page;
  const base = {
    listUrl: url,
    items: list.items,
    pagination: list.pagination,
    counts: list.counts || {},
    filterKeys: Object.keys(list.filters || {}),
    ...list.filters,
    listLoading: false,
    listError: "",
    listRequest: 0,
    searchTimer: null,

    init() {
      // The filters defaults are the values of the first page (the page may be opened with filters in the url)
      this.filterDefaults = { ...defaults, ...this.filterDefaults };
      this.filterKeys.forEach((key) =>
        this.$watch(key, () => (key === "search" ? this.debouncedLoad() : this.loadList(1)))
      );
      if (typeof this.setup === "function") this.setup();
    },

    debouncedLoad() {
      clearTimeout(this.searchTimer);
      this.searchTimer = setTimeout(() => this.loadList(1), 300);
    },

    // Parameters of the url: only the values different from the defaults
    listParams(page) {
      const params = new URLSearchParams();
      this.filterKeys.forEach((key) => {
        // An empty value is written when the default is another one (e.g. ?filter= for "all" when the default is "todo")
        const value = String(this[key] ?? "").trim();
        if (value !== String(this.filterDefaults[key] ?? "")) params.set(key, value);
      });
      if (this.pagination.per_page !== defaultPerPage) params.set("per_page", this.pagination.per_page);
      if (page > 1) params.set("page", page);
      return params;
    },

    // Loads a page (by default the current one again, e.g. after an action on the items)
    async loadList(page = this.pagination.page) {
      const request = ++this.listRequest;
      const params = this.listParams(page);
      this.listLoading = true;
      this.listError = "";
      try {
        const json = await requestJson(`${this.listUrl}?${params.toString() ? params + "&" : ""}format=json`);
        if (request !== this.listRequest) return;
        this.items = json.items;
        this.pagination = json.pagination;
        this.counts = json.counts || {};
        // The other parameters of the url (e.g. ?periode= of another part of the page) are kept
        const address = new URL(window.location);
        [...this.filterKeys, "page", "per_page"].forEach((key) => address.searchParams.delete(key));
        this.listParams(json.pagination.page).forEach((value, key) => address.searchParams.set(key, value));
        window.history.replaceState(null, "", address);
        if (typeof this.afterLoad === "function") this.afterLoad();
      } catch (error) {
        if (request === this.listRequest) this.listError = error.message || "La liste n'a pas pu être chargée. Réessayez.";
      } finally {
        if (request === this.listRequest) this.listLoading = false;
      }
    },

    goToPage(page) {
      if (page < 1 || page > this.pagination.pages || page === this.pagination.page) return;
      this.loadList(page).then(() => document.getElementById("list-top")?.scrollIntoView({ behavior: "smooth", block: "start" }));
    },

    setPerPage(perPage) {
      this.pagination.per_page = parseInt(perPage);
      this.loadList(1);
    },

    // Page numbers around the current page, null for "…" (same rule as ListQuery::pageNumbers())
    get pageLinks() {
      const { page, pages } = this.pagination;
      const links = [];
      for (let number = 1; number <= pages; number++) {
        if (number === 1 || number === pages || Math.abs(number - page) <= 1) links.push(number);
        else if (links[links.length - 1] !== null) links.push(null);
      }
      return links;
    },
  };
  return Object.defineProperties(base, Object.getOwnPropertyDescriptors(extra));
}

/**
 * Appearance animation of an item of a list (news, section leaders): it fades in when it comes on screen,
 * after the items before it in the same group ("x-init="appearOnView($el, index)"). Nothing moves if the user
 * asked the system to reduce the animations (CSS .appear-wait of input.css).
 * @param {HTMLElement} element - The item.
 * @param {number} position - Its position in the group loaded at the same time (delay of 90 ms each).
 */
function appearOnView(element, position = 0) {
  element.style.setProperty("--appear-delay", `${Math.min(position, 8) * 90}ms`);
  element.classList.add("appear-wait");
  if (!("IntersectionObserver" in window)) {
    element.classList.add("appear-in");
    return;
  }
  const observer = new IntersectionObserver(
    (entries) => {
      if (entries.some((entry) => entry.isIntersecting)) {
        element.classList.add("appear-in");
        observer.disconnect();
      }
    },
    { threshold: 0.1 }
  );
  observer.observe(element);
}
