document.addEventListener("DOMContentLoaded", function() {
    const today = new Date();
    const day = today.getDate();

    const tile = document.querySelector(`.tile[data-day='${day}']`);

    if (tile) {
        tile.classList.add("pulse-animation");

        tile.addEventListener("click", function() {
            tile.classList.remove("pulse-animation");
        });
    }

    const tiles = document.querySelectorAll(".tile");
    const modal = document.getElementById("modal");
    const modalBody = document.getElementById("modal-body");
    const modalText = document.getElementById("modal-text");
    const closeModal = document.querySelector(".close");

    tiles.forEach(tile => {
        tile.addEventListener("click", function() {
            const tileDay = parseInt(tile.getAttribute("data-day"), 10);

            // Проверка, чтобы плитки с датой больше сегодняшней не открывались + 0
            if ((tileDay > day) || (tileDay == '0')) {
                tile.classList.add("shake-animation");
                setTimeout(() => {
                    tile.classList.remove("shake-animation");
                }, 500);
                return;
            }

            const type = tile.getAttribute("data-type");
            const content = tile.getAttribute("data-content");
            const text = tile.getAttribute("data-text");

            modalBody.innerHTML = "";

            // устанавливаем текст только если он не пустой и тип не текст
            if (type !== "text") {
                modalText.textContent = text || "";
            } else {
                modalText.textContent = "";
            }

            if (type === "image") {
                const img = document.createElement("img");
                img.src = content;
                img.style.maxWidth = "100%";
                img.style.height = "auto";
                modalBody.appendChild(img);
            } else if (type === "video") {
                const iframe = document.createElement("iframe");
                iframe.width = "560";
                iframe.height = "315";
                iframe.src = content;
                iframe.frameBorder = "0";
                iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
                iframe.allowFullscreen = true;
                modalBody.appendChild(iframe);
            } else if (type === "text") {
                const p = document.createElement("p");
                p.textContent = text;
                modalBody.appendChild(p);
            }

            modal.style.display = "block";

// закрытие модального окна и отображение содержимого в плитке
            closeModal.onclick = function() {
                modal.style.display = "none";
                modalBody.innerHTML = "";
                if (type === "image") {
                    tile.innerHTML = `<img src="${content}" alt="Image" style="width: 100%; height: 100%; object-fit: cover;">${text ? `<p>${text}</p>` : ""}`;
                    tile.querySelector("img").style.display = "block";
                } else if (type === "video") {
                    tile.innerHTML = `<iframe width="200" height="113" src="${content}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>${text ? `<p>${text}</p>` : ""}`;
                    tile.classList.add("black-background");
                } else if (type === "text") {
                    tile.innerHTML = `<p>${text}</p>`;
                }

                // сжимаем видео
                const videoElement = tile.querySelector("iframe");
                if (videoElement) {
                    videoElement.style.width = "180px";
                    videoElement.style.height = "auto";
                }

                tile.classList.add("small-font");
            };

// закрытие модального окна при клике вне его
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    modalBody.innerHTML = "";
                    if (type === "image") {
                        tile.innerHTML = `<img src="${content}" alt="Image">${text ? `<p>${text}</p>` : ""}`;
                        tile.querySelector("img").style.display = "block";
                    } else if (type === "video") {
                        tile.innerHTML = `<iframe width="200" height="113" src="${content}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>${text ? `<p>${text}</p>` : ""}`;
                        tile.classList.add("black-background");
                    } else if (type === "text") {
                        tile.innerHTML = `<p>${text}</p>`;
                    }

                    const videoElement = tile.querySelector("iframe");
                    if (videoElement) {
                        videoElement.style.width = "180px";
                        videoElement.style.height = "auto";
                    }

                    tile.classList.add("small-font");
                }
            };
        });
    });
});