export class CollectionInputCustomElement extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        const {targetId, label} = this.dataset;
        const inputElement = document.querySelector(`#${targetId}`);

        if (!inputElement || !(inputElement instanceof HTMLElement)) {
            throw new Error(`No input model found for targetId ${targetId}`);
        }

        const inputPrototype = inputElement.dataset.prototype;

        if (!inputPrototype) {
            throw new Error(`No input prototype found for targetId ${targetId}`);
        }

        const createdElement = document.createElement('div');
        createdElement.innerHTML = inputPrototype
            .replace('__name__label__', label ?? "")
            .replace('__name__', (Math.random() * 100).toString());

        inputElement.parentElement!.appendChild(createdElement);
    }
}
