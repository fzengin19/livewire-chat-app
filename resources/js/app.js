import "./bootstrap";

// Fonksiyon: Pencere boyutu değiştikçe kontrol et
// setTimeout(()=>{
//     window.Echo.channel('test').listen('.App\\Events\\SendMessageEvent', (e) => {
//         console.log(e);
//     });
//     },200)

function adjustChatView() {
    var windowWidth = window.innerWidth;

    if (windowWidth < 768) {
        // Mobil ekran görünümü
        $("#chatListContainer").hide();
        $("#chatContainer").show();
    } else {
        // Büyük ekran görünümü
        $("#chatListContainer").show();
        $("#chatContainer").show();
    }
}

function handleChatBodyScroll() {
    var top = $("#chatContainer .chatbox_body").scrollTop();
    if (top === 0) {
        window.livewire.emit("loadmore");
    }
}

$(document).on("click", ".chat-item", function () {
    var windowWidth = window.innerWidth;
    if (windowWidth < 768) {
        $("#chatListContainer").hide();
        $("#chatContainer").show();
    } else {
        // Büyük ekran görünümü
        $("#chatListContainer").show();
        $("#chatContainer").show();
    }
});

window.addEventListener("chatSelected", function () {
    adjustChatView();
    $("#chatContainer .chatbox_body").scrollTop(
        $("#chatContainer .chatbox_body")[0].scrollHeight
    );

    let height = $("#chatContainer .chatbox_body")[0].scrollHeight;
    window.livewire.emit("updateHeight", {
        height: height,
    });
});

// Event: Pencere boyutu değiştiğinde
$(window).resize(function () {
    adjustChatView();
});

// Event: "return" class'a tıklanınca
$(document).on("click", "#closeActiveChat", function () {
    $("#chatListContainer").show();
    $("#chatContainer").hide();
});

// Event: chatBody scroll olayı
$(document).on("scroll", "#chatContainer .chatbox_body", handleChatBodyScroll);
