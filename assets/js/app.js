(function () {
  var toggle = document.querySelector('[data-menu-toggle]');
  var nav = document.querySelector('[data-main-nav]');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      nav.classList.toggle('open');
    });
  }

  function updateCountdowns() {
    document.querySelectorAll('tr[data-start]').forEach(function (row) {
      var raw = row.getAttribute('data-start') || '';
      var start = new Date(raw.replace(' ', 'T') + 'Z');
      var diff = Math.floor((start.getTime() - Date.now()) / 1000);
      var target = row.querySelector('.countdown');
      if (!target) return;

      if (Number.isNaN(start.getTime())) {
        target.textContent = 'Invalid date';
        return;
      }

      if (diff <= 0) {
        target.textContent = 'Live / Closed';
        return;
      }

      var days = Math.floor(diff / 86400);
      var hrs = String(Math.floor((diff % 86400) / 3600)).padStart(2, '0');
      var mins = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
      var secs = String(diff % 60).padStart(2, '0');
      target.textContent = (days > 0 ? days + 'd ' : '') + hrs + ':' + mins + ':' + secs;
    });
  }

  var reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && reveals.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('show'); });
  }

  updateCountdowns();
  setInterval(updateCountdowns, 1000);
})();
