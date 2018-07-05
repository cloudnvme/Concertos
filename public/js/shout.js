class ChatboxController {
    constructor() {
        this.chatbox = document.querySelector("#chat");
        this.forceScroll = true;
        let token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
        this.headers = new Headers({
            "X-CSRF-TOKEN": token
        });
        this.nextBatch = parseInt(this.chatbox.getAttribute("data-last"));
        this.time = Date.now();
        this.counter = 3;
        this.autoUpdateId = null;
        this.updating = false;

        this.chatbox.addEventListener("scroll", (e) => {
            this.forceScroll = this.chatbox.scrollHeight - this.chatbox.scrollTop == this.chatbox.clientHeight;
        });
    }

    scrollDown() {
        this.chatbox.scrollTop = this.chatbox.scrollHeight;
    }

    delay() {
        return 1000 * Math.random() * Math.pow(2, this.counter);
    }

    resetUpdate() {
        this.counter = 3;
        if (this.autoUpdateId !== null) {
            clearTimeout(this.autoUpdateId);
            this.autoUpdate();
        }
    }

    async sendMessage(message) {
        let body = new URLSearchParams();
        body.append('message', message);
        let r = await fetch("/shoutbox/send", {
            method: "POST",
            body: body,
            headers: this.headers,
            credentials: "same-origin"
        });

        this.forceScroll = true;
        await this.updateMessages();
    }

    async updateMessages(after = null) {
        if (this.updating) {
            return;
        }
        this.updating = true;
        after = after === null ? this.nextBatch : after;
        let r = await fetch("/shoutbox/messages/" + after.toString(), {
            method: "GET",
            headers: this.headers,
            credentials: "same-origin"
        });
        let j = await r.json();
        this.chatbox.insertAdjacentHTML('beforeend', j.data);
        if (this.nextBatch === j.nextBatch) {
            this.counter = Math.min(this.counter + 1, 6);
        } else {
            this.resetUpdate();
        }
        this.nextBatch = j.nextBatch;
        if (this.forceScroll) {
            this.scrollDown();
        }
        this.updating = false;
    }

    async autoUpdate() {
        let delay = this.delay();

        this.autoUpdateId = setTimeout(() => {
            requestAnimationFrame(() => {
                this.updateMessages();
                this.autoUpdate();
            });
        }, delay);
    }
}

let controller = new ChatboxController();
window.onload = () => {
    controller.scrollDown();
};

let sendButton = document.querySelector("#send-message");
let messageText = document.querySelector("#chat-message");
let editor = makeEditor(messageText);

sendButton.addEventListener("click", (e) => {
    editor.updateOriginal();
    controller.sendMessage(editor.val());
    editor.val('');
});

editor.keyUp((e) => {
    if (e.key == "Enter" && !e.shiftKey) {
        controller.sendMessage(editor.val());
        editor.val('');
    }
});
controller.autoUpdate();