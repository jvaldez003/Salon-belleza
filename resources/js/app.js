import './bootstrap';

import Alpine from 'alpinejs';
import { iniciarLuzAgua } from './luz-agua';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', iniciarLuzAgua);
