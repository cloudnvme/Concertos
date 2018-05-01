function updateTorrentName() {
    let name = document.querySelector("#title");
    let torrent = document.querySelector("#torrent");
    let fileEndings = [".mkv.torrent", ".torrent"];
    let allowed = ["1.0", "2.0", "5.1", "7.1", "H.264"];
    let separators = ["-", " ", "."];
    if (name !== null && torrent !== null) {
        let value = torrent.value.split('\\').pop().split('/').pop();
        fileEndings.forEach(function (e) {
            if (value.endsWith(e)) {
                value = value.substr(0, value.length - e.length);
            }
        });
        value = value.replace(/\./g, " ");
        allowed.forEach(function (a) {
            search = a.replace(/\./g, " ");
            let replaceIndexes = [];
            let pos = value.indexOf(search);
            while (pos !== -1) {
                let start = pos > 0 ? value[pos - 1] : " ";
                let end = pos + search.length < value.length ? value[pos + search.length] : " ";
                if (separators.includes(start) && separators.includes(end)) {
                    replaceIndexes.push(pos);
                }
                pos = value.indexOf(search, pos + search.length);
            }
            newValue = "";
            ignore = 0;
            for (let i = 0; i < value.length; ++i) {
                if (ignore > 0) {
                    --ignore;
                } else if (replaceIndexes.length > 0 && replaceIndexes[0] == i) {
                    replaceIndexes.shift();
                    newValue += a;
                    ignore = a.length - 1;
                } else {
                    newValue += value[i];
                }
            }
            value = newValue;
        });
        name.value = value;
    }
}

document.querySelector("#add").addEventListener("click", () => {
    var optionHTML = '<div class="flex flex--fluid"><div class="heading">Mediainfo</div><textarea rows="2" class="textarea" name="mediainfo" cols="50" id="mediainfo" placeholder="Paste MediaInfo Dump Here"></textarea></div>';
    document.querySelector(".parser").innerHTML = optionHTML;
    //$('.parser').append(optionHTML);
});

document.querySelector("#torrent").addEventListener("input", () => {
    updateTorrentName();
});