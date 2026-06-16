// Intentionally keep placeholder links from becoming broken /go/ URLs.
document.querySelectorAll('a[href="#"]').forEach((link) => {
  link.addEventListener('click', (event) => event.preventDefault());
});
