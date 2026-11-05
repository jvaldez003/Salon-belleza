import './bootstrap';

import Alpine from 'alpinejs';
import { iniciarLuzAgua } from './luz-agua';
import { registrarHero } from './hero';

window.Alpine = Alpine;

registrarHero(Alpine);
Alpine.start();

document.addEventListener('DOMContentLoaded', iniciarLuzAgua);
