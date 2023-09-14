import './bootstrap';
import '~resources/scss/app.scss';
import * as bootstrap from 'bootstrap';
import lottie from 'lottie-web';
import { defineElement } from 'lord-icon-element';
defineElement(lottie.loadAnimation);
import.meta.glob([
    '../img/**'
])
