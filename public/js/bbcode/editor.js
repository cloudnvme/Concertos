document.addEventListener("DOMContentLoaded", () => {
    let textarea = document.querySelector("#bbcode-editor");
    let button = document.querySelector("#bbcode-button");
    if (textarea !== null) {
        let editor = makeEditor(textarea);
        if (button !== null) {
            button.addEventListener('click', (e) => {
                editor.updateOriginal();
            });
        }
    }
});