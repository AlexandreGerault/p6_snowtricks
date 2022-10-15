/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import {CollectionInputCustomElement} from "./components/CollectionInputCustomElement";
import './collection'
import Alpine from 'alpinejs'
import { createRoot } from 'react-dom/client';
import TrickList from "./components/Tricks/TrickList";

// @ts-ignore
window.Alpine = Alpine

customElements.define('collection-input', CollectionInputCustomElement);

Alpine.start()

document.addEventListener('DOMContentLoaded', () => {
    const sectionElement = document.querySelector('#trick-overviews');

    if (!sectionElement) {
        return;
    }

    const root = createRoot(sectionElement);
    root.render(<TrickList />);
})
