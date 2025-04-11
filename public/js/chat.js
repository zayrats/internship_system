document.addEventListener("DOMContentLoaded", function () {
    const chatForm = document.getElementById("chat-form");
    const chatInput = document.getElementById("chat-input");
    const chatMessages = document.getElementById("chat-messages");

    // Fungsi untuk memuat pesan terbaru
    function loadMessages() {
        fetch("{{ route('chat.get') }}") // Ganti dengan endpoint backend kamu
            .then(response => response.json())
            .then(data => {
                chatMessages.innerHTML = ""; // Kosongkan chat sebelum diisi ulang
                data.messages.forEach(msg => {
                    let messageElement = document.createElement("div");
                    messageElement.className = "p-2 bg-gray-200 rounded-lg my-1";
                    messageElement.textContent = msg.user + ": " + msg.message;
                    chatMessages.appendChild(messageElement);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll ke bawah
            })
            .catch(error => console.error("Error loading messages:", error));
    }

    // Kirim pesan baru via AJAX
    chatForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const message = chatInput.value.trim();
        if (message === "") return;

        fetch("{{ route('chat.send') }}", { // Ganti dengan route backend kamu
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // Untuk Laravel
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(() => {
            chatInput.value = ""; // Kosongkan input setelah terkirim
            loadMessages(); // Perbarui chat setelah kirim pesan
        })
        .catch(error => console.error("Error sending message:", error));
    });

    // Auto-refresh chat setiap 3 detik
    setInterval(loadMessages, 3000);

    // Load pertama kali
    loadMessages();
});
