document.addEventListener("DOMContentLoaded", () => {
    let posts = document.querySelectorAll(".post");
    let editor = document.querySelector("#topic-response");
    let token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
    let headers = new Headers({
        "X-CSRF-TOKEN": token
    });

    console.log(token);
    for (post of posts) {
        let id = post.id.substring("post-".length);
        let button = post.querySelector(".quote-btn");
        button.addEventListener('click', async (e) => {
            let url = "/post/" + id + "/quote";
            console.log(url)
            let rsp = await fetch(url, {
                method: "GET",
                headers: headers,
                credentials: "same-origin"
            });
            let j = await rsp.json();
            editor.value = j.data;
        });
    }
});