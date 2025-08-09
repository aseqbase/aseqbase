window.addEventListener('scroll', () => {
  const tolerance = 50;
  const atTop = window.scrollY <= tolerance;
  const atBottom = window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - tolerance;
  const atMiddle = !atTop && !atBottom;
  const atLeft = window.scrollX <= tolerance;
  const atRight = window.scrollX + window.innerWidth >= document.documentElement.scrollWidth - tolerance;
  const atCenter = !atTop && !atBottom;

  document.querySelectorAll('.top-show').forEach(el => {
    el.style.display = atTop ? 'revert' : 'none';
  });
  document.querySelectorAll('.middle-show').forEach(el => {
    el.style.display = atMiddle ? 'revert' : 'none';
  });
  document.querySelectorAll('.bottom-show').forEach(el => {
    el.style.display = atBottom ? 'revert' : 'none';
  });
  document.querySelectorAll('.top-show').forEach(el => {
    el.style.display = atLeft ? 'revert' : 'none';
  });
  document.querySelectorAll('.center-show').forEach(el => {
    el.style.display = atCenter ? 'revert' : 'none';
  });
  document.querySelectorAll('.right-show').forEach(el => {
    el.style.display = atRight ? 'revert' : 'none';
  });

  document.querySelectorAll('.top-hide').forEach(el => {
    el.style.display = atTop ? 'none' : 'revert';
  });
  document.querySelectorAll('.middle-hide').forEach(el => {
    el.style.display = atMiddle ? 'none' : 'revert';
  });
  document.querySelectorAll('.bottom-hide').forEach(el => {
    el.style.display = atBottom ? 'none' : 'revert';
  });
  document.querySelectorAll('.left-hide').forEach(el => {
    el.style.display = atLeft ? 'none' : 'revert';
  });
  document.querySelectorAll('.center-hide').forEach(el => {
    el.style.display = atCenter ? 'none' : 'revert';
  });
  document.querySelectorAll('.right-hide').forEach(el => {
    el.style.display = atRight ? 'none' : 'revert';
  });

  
  document.querySelectorAll('.top-visible').forEach(el => {
    el.style.visibility = atTop ? 'visible' : 'hidden';
  });
  document.querySelectorAll('.middle-visible').forEach(el => {
    el.style.visibility = atMiddle ? 'visible' : 'hidden';
  });
  document.querySelectorAll('.bottom-visible').forEach(el => {
    el.style.visibility = atBottom ? 'visible' : 'hidden';
  });
  document.querySelectorAll('.left-visible').forEach(el => {
    el.style.visibility = atLeft ? 'visible' : 'hidden';
  });
  document.querySelectorAll('.center-visible').forEach(el => {
    el.style.visibility = atCenter ? 'visible' : 'hidden';
  });
  document.querySelectorAll('.right-visible').forEach(el => {
    el.style.visibility = atRight ? 'visible' : 'hidden';
  });

  document.querySelectorAll('.top-invisible').forEach(el => {
    el.style.visibility = atTop ? 'hidden' : 'visible';
  });
  document.querySelectorAll('.middle-invisible').forEach(el => {
    el.style.visibility = atMiddle ? 'hidden' : 'visible';
  });
  document.querySelectorAll('.bottom-invisible').forEach(el => {
    el.style.visibility = atBottom ? 'hidden' : 'visible';
  });
  document.querySelectorAll('.left-invisible').forEach(el => {
    el.style.visibility = atLeft ? 'hidden' : 'visible';
  });
  document.querySelectorAll('.center-invisible').forEach(el => {
    el.style.visibility = atCenter ? 'hidden' : 'visible';
  });
  document.querySelectorAll('.right-invisible').forEach(el => {
    el.style.visibility = atRight ? 'hidden' : 'visible';
  });
});