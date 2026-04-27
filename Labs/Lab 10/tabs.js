// Option tab switching for Lab 10 instructions
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.options').forEach(function (group) {
    var tabs = group.querySelectorAll('.options-tabs button');
    var panels = group.querySelectorAll('.option-panel');

    tabs.forEach(function (tab, i) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) { t.classList.remove('active'); });
        panels.forEach(function (p) { p.classList.remove('active'); });
        tab.classList.add('active');
        panels[i].classList.add('active');
      });
    });
  });
});
