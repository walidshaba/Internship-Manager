document.addEventListener('DOMContentLoaded', function () {
  const burger = document.getElementById('burger');
  const navbar = document.getElementById('left-navbar');

  burger.addEventListener('click', function () {
    navbar.classList.toggle('active');
  });
});
