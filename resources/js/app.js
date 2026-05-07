import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';

import $ from 'jquery';

window.Alpine = Alpine;

Alpine.start();

window.$ = window.jQuery = $;
