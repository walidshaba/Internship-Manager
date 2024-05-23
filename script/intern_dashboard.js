document.addEventListener('DOMContentLoaded', function () {
  var burger = document.getElementById('burger');
  var navbar = document.getElementById('left-navbar');

  burger.addEventListener('click', function () {
    navbar.classList.toggle('active');
  });
});
