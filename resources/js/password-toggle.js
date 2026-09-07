document.addEventListener('click', function (e) {
  const btn = e.target.closest('.js-password-toggle');
  if (!btn) return;
  const targetId = btn.getAttribute('data-password-target');
  const input = document.getElementById(targetId);
  if (!input) return;
  if (input.type === 'password') {
    input.type = 'text';
    btn.classList.add('text-primary');
    const show = btn.querySelector('.icon-show');
    const hide = btn.querySelector('.icon-hide');
    if (show) show.classList.add('hidden');
    if (hide) hide.classList.remove('hidden');
  } else {
    input.type = 'password';
    btn.classList.remove('text-primary');
    const show = btn.querySelector('.icon-show');
    const hide = btn.querySelector('.icon-hide');
    if (show) show.classList.remove('hidden');
    if (hide) hide.classList.add('hidden');
  }
});
