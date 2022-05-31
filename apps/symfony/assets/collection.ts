import {v4 as uuid4} from 'uuid';

const newItem = (e: Event) => {
    const currentTargetElement = e.currentTarget;

    if (!(currentTargetElement instanceof HTMLElement) || !currentTargetElement?.dataset) {
        return;
    }

    const targetId = currentTargetElement.dataset.collection;
    if (!targetId) {
        return;
    }

    const collectionHolder = document.getElementById(targetId);
    if (!collectionHolder || !(collectionHolder instanceof HTMLElement)) {
        return;
    }

    const item = document.createElement("div");
    item.innerHTML = collectionHolder
        .dataset
        ?.prototype
        ?.replace(
            /__name__/g,
            uuid4()
        ) ?? '';

    if (!collectionHolder) {
        return;
    }

    item.querySelector(".remove-button")?.addEventListener("click", () => item.remove());

    collectionHolder.appendChild(item);
};

document
    .querySelectorAll('.remove-button')
    .forEach(btn => btn.addEventListener("click", (e) => {
        const element = e.currentTarget as HTMLElement;

        if (!element) {
            return;
        }

        element.closest(".input-group")?.remove()
    }));

document
    .querySelectorAll('.add-button')
    .forEach(btn => btn.addEventListener("click", newItem));
