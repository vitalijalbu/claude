<div id="map" class="aspect-w-16 aspect-h-9 border border-outline rounded-xl h-[400px] w-full"></div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Imposta il centro della mappa su Brescia (latitudine, longitudine)
    const map = L.map('map').setView([45.5386, 10.2118], 13); // Centro su Brescia

    // Aggiungi il layer di OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Aggiungi un cerchio approssimativo su Brescia
    const circle = L.circle([45.5386, 10.2118], {
      color: 'blue',
      fillColor: 'blue', 
      fillOpacity: 0.2, 
      weight: 1,
      radius: 1000, 
    }).addTo(map);

    // Aggiungi un popup al cerchio
    circle.bindPopup("Cerchio approssimativo su Brescia.");
  });
</script>
