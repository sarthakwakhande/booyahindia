(function () {
  function updateCountdowns() {
    document.querySelectorAll('tr[data-start]').forEach(function (row) {
      var start = new Date(row.getAttribute('data-start').replace(' ', 'T'));
      var diff = Math.floor((start - new Date()) / 1000);
      var target = row.querySelector('.countdown');
      if (!target) return;

      if (diff <= 0) {
        target.textContent = 'Live / Closed';
        return;
      }

      var hrs = String(Math.floor(diff / 3600)).padStart(2, '0');
      var mins = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
      var secs = String(diff % 60).padStart(2, '0');
      target.textContent = hrs + ':' + mins + ':' + secs;
    });
  }

  updateCountdowns();
  setInterval(updateCountdowns, 1000);
})();
