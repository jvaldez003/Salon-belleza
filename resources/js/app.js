import './bootstrap';

import Alpine from 'alpinejs';
import { iniciarLuzAgua } from './luz-agua';
import { registrarHero } from './hero';
import { registrarCarta } from './carta';

window.Alpine = Alpine;

registrarHero(Alpine);
registrarCarta(Alpine);
Alpine.start();

document.addEventListener('DOMContentLoaded', iniciarLuzAgua);
