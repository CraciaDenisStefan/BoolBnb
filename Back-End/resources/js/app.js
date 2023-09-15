import './bootstrap';
import '~resources/scss/app.scss';
import * as bootstrap from 'bootstrap';
import.meta.glob([
    '../img/**'
])

// Ottieni l'elemento HTML con id 'description'
let descriptElement = document.getElementById('description');

// Estrai il testo dall'elemento
let descript = descriptElement.textContent;

function truncateText(descript) {
    if (descript.length > 50) {
        return descript.substr(0, 50) + '...';
    }
    return descript;
}

// Utilizza la funzione per abbreviare il testo
let truncatedDescription = truncateText(descript);

// Assegna il testo abbreviato all'elemento HTML
descriptElement.textContent = truncatedDescription;