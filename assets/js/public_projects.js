document.getElementById('messageForm').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Your message has been sent! Thank you for reaching out.');
  const modal = bootstrap.Modal.getInstance(document.getElementById('messageModal'));
  modal.hide();
  this.reset();
});

