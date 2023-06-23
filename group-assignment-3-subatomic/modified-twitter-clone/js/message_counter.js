const area  = document.querySelector('#message');
const counter  = document.querySelector('#counter');

if (area != null) {
  area.addEventListener('input', updateCounter);

  function updateCounter() {
    counter.textContent = area.value.length;
  }
}
