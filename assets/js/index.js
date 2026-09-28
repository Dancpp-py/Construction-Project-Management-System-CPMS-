const carousel = document.querySelector('#teamCarousel');

carousel.addEventListener('slid.bs.carousel', () => {
  
const allFlips = document.querySelectorAll('.flip-card-inner');

allFlips.forEach(flip => {
    flip.style.animation = 'none';
  });

  const activeFlip = document.querySelector('.carousel-item.active .flip-card-inner');
  
  if (activeFlip) {
    void activeFlip.offsetWidth;
    activeFlip.style.animation = 'flipCard 2.5s ease-in-out forwards';
  }
});
