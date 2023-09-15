import './bootstrap';
import '~resources/scss/app.scss';
import * as bootstrap from 'bootstrap';
import.meta.glob([
    '../img/**'
])

// Seleziona tutti gli elementi con la classe "truncate-text"
let descriptElements = document.querySelectorAll('.truncate-text');

// Specifica il numero massimo di caratteri
let maxLength = 50;

// Itera su tutti gli elementi e applica la funzione a ciascuno
descriptElements.forEach(function (descriptElement) {
    let descript = descriptElement.textContent;

    // Controlla se la lunghezza del testo supera il numero massimo di caratteri
    if (descript.length > maxLength) {
        // Tronca il testo alla lunghezza massima
        descript = descript.substr(0, maxLength) + '...';
    }

    // Assegna il testo troncato all'elemento HTML
    descriptElement.textContent = descript;
});





