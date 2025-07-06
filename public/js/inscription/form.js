const radioNew = document.getElementById('modeNew'),
    radioRe = document.getElementById('modeRe'),
    secNew = document.getElementById('newSec'),
    secRe = document.getElementById('reSec');

function toggleSections() {
    secNew.classList.toggle('active', radioNew.checked);
    secRe.classList.toggle('active', radioRe.checked);
}

radioNew.addEventListener('change', toggleSections);
radioRe.addEventListener('change', toggleSections);
toggleSections();
