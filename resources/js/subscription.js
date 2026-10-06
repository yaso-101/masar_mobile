// Copy button on the subscription page (copies the FIB number to pay to)
window.copyPayNumber = async function () {
    const number = document.getElementById("pay-to-number")?.dataset.copy;
    const label = document.getElementById("copy-label");
    if (!number) return;

    try {
        await navigator.clipboard.writeText(number);
    } catch {
        // Older phone WebViews without the Clipboard API: copy through a hidden text box
        const box = document.createElement("textarea");
        box.value = number;
        box.style.position = "fixed";
        box.style.opacity = "0";
        document.body.appendChild(box);
        box.select();
        document.execCommand("copy");
        box.remove();
    }

    if (label) {
        label.innerText = "Copied!";
        setTimeout(() => (label.innerText = "Copy"), 2000);
    }
};
