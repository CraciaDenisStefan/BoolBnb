@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="my-5">Numero di visualizzazioni nell'ultima settimana</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-8 offset-2 mt-5">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myChart');

// Recupera i dati passati dal controller
const viewsByDay = @json($viewsByDay); // Assicurati che i dati siano correttamente convertiti in JSON

const labels = Object.keys(viewsByDay);
const data = Object.values(viewsByDay).map(views => views.length);

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'Visualizzazioni',
      data: data,
      borderWidth: 1,
      backgroundColor: 'rgba(75, 192, 192, 0.2)', // Colore delle barre
      borderColor: 'rgba(75, 192, 192, 1)', // Colore dei bordi delle barre
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
</script>
@endsection
