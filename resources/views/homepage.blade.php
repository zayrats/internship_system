@extends('master')
@section('content')
    <div class="min-h-screen p-3  bg-gray-100 dark:bg-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Section 1: Hero -->
            <div class="flex flex-col bg-gray-500 bg-opacity-50 shadow-md rounded-xl overflow-hidden">
                <div class="p-6 lg:p-8">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Kisahmu jadi bekalku</h3>
                    <p class="mt-2 text-gray-900 dark:text-white">
                        Temukan informasi magang yang komprehensif dari rekan-rekan senior, jelajahi kisah sukses mereka,
                        dan
                        terhubung langsung.
                    </p>
                    <a href="{{ route('internship') }}"
                        class="mt-4 inline-flex items-center px-5 py-2.5 text-white bg-gray-700 rounded-lg shadow-md hover:bg-gray-700 transition font-semibold">
                        Ayo Buat Kisahmu
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <img class="w-full h-52 object-cover"
                    src="https://www.pens.ac.id/wp-content/uploads/2023/12/cover-web-ionic-1080x675.jpg" alt="internship">
            </div>

            <!-- Section 2: Maps -->
            <div class="flex flex-col bg-gray-500 bg-opacity-50 shadow-md rounded-xl overflow-hidden">
                <a href="{{ route('maps') }}" class="group">
                    <img class="w-full h-52 object-cover transition-transform duration-300 group-hover:scale-105"
                        src="http://2.bp.blogspot.com/-zsKt2Xv7pb0/UV2PedQ4tgI/AAAAAAAAACY/09wKfwHFdSM/s1600/peta+perekonomian.gif"
                        alt="maps">
                </a>
                <div class="p-6 lg:p-8">
                    <h5 class="text-2xl font-bold text-gray-900 dark:text-white">Magang di Seluruh Indonesia</h5>
                    <p class="mt-2 text-gray-900 dark:text-white">
                        Lihat siswa yang telah melakukan magang di seluruh Indonesia.
                    </p>
                </div>
            </div>
        </div>

        <!-- Chat Section -->
        <div class="mt-10 bg-gray-600 bg-opacity-50 shadow-md rounded-xl overflow-hidden">
            <h3 class="text-center text-2xl font-bold py-5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">Forum Diskusi</h3>
            <div class="p-6">
                <div class="max-w-6xl mx-auto">
                    <!-- Tempat Menampilkan Pesan Chat -->
                    <div id="chat-messages"
                        class="min-h-[500px] overflow-y-auto bg-gray-100 dark:bg-gray-700 p-4 rounded-lg border border-gray-300 shadow-sm flex flex-col-reverse">
                        <p class="text-gray-900 dark:text-white text-center">Memuat pesan...</p>
                    </div>

                    <!-- Form Chat -->
                    <form id="chat-form" class="mt-4 flex gap-2">
                        <textarea id="chat-input" rows="1"
                            class="flex-1 p-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-gray-600 focus:border-gray-600"
                            placeholder="Tulis pesan..."></textarea>
                        <button type="submit"
                            class="p-3 bg-gray-800 text-white rounded-lg shadow-md hover:bg-gray-700 transition">
                            <svg class="w-6 h-6 rotate-90" fill="currentColor" viewBox="0 0 18 20">
                                <path
                                    d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatMessages = document.getElementById("chat-messages");
            const chatForm = document.getElementById("chat-form");
            const chatInput = document.getElementById("chat-input");

            // 🔄 Fetch pesan chat saat halaman dimuat
            function fetchMessages() {
                fetch("/chat/messages")
                    .then(response => response.json())
                    .then(data => {
                        chatMessages.innerHTML = ""; // Kosongkan chat sebelum diisi ulang

                        // Tambahkan pesan ke dalam container (biar urutan benar)
                        data.forEach(chat => {
                            let messageElement = document.createElement("div");
                            messageElement.classList.add("mb-2");
                            messageElement.innerHTML = `
                        <strong class=" text-blue-500">${chat.user.username}</strong>
                        <p class="text-gray-900 dark:text-white">${chat.message}</p>
                    `;
                            chatMessages.appendChild(
                                messageElement); // Tambahkan pesan dari atas ke bawah
                        });
                    })
                    .catch(error => console.error("Error fetching messages:", error));
            }

            // ▶️ Jalankan fetchMessages tiap 5 detik untuk update otomatis
            setInterval(fetchMessages, 5000);
            fetchMessages(); // Jalankan pertama kali saat halaman dimuat

            // 📨 Kirim pesan ke server
            chatForm.addEventListener("submit", function(event) {
                event.preventDefault();
                const message = chatInput.value.trim();
                if (message === "") return;

                fetch("/chat/send", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        chatInput.value = ""; // Kosongkan input setelah kirim
                        fetchMessages(); // Ambil pesan terbaru
                    })
                    .catch(error => console.error("Error sending message:", error));
            });
        });
    </script>



    {{-- <div
        class="flex flex-col  bg-white border border-gray-200 h-auto  shadow-sm md:flex-col md:max-w-full  dark:border-gray-700 dark:bg-gray-500  ">
        <h3 class="pt-5 mb-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
            Cerita Mahasiswa
        </h3>

        <div id="indicators-carousel" class="relative w-full" data-carousel="static">
            <!-- Carousel wrapper -->
            <div id="testimonial-carousel" class="relative h-auto overflow-hidden rounded-lg md:h-96 mx-20 items-center justify-center ">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out p-8 px-20 mx-20 " data-carousel-item="active">
                    <figure
                        class="grid place-items-center  text-center bg-white border rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 min-h-[300px]  min-w-[900px]">

                        <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Very easy this was to integrate
                            </h3>
                            <p class="my-4">"If you care for your time, I hands down would go with this."</p>
                        </blockquote>
                        <figcaption class="flex items-center justify-center">
                            <img class="rounded-full w-9 h-9"
                                src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/karen-nelson.png"
                                alt="profile picture">
                            <div class="ms-3">
                                <div class="font-medium dark:text-white">Bonnie Green</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Developer at Open AI</div>
                            </div>
                        </figcaption>
                    </figure>
                </div>

                <!-- Item 2 -->
                <div class="p-8 px-20 mx-20 hidden duration-700 ease-in-out" data-carousel-item>
                    <figure
                        class="grid place-items-center p-8 text-center bg-white border rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 min-h-[300px] mx-auto min-w-[900px]">
                        <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Solid foundation for any
                                project</h3>
                            <p class="my-4">"Designing with Figma components that can be easily translated to Tailwind
                                CSS is a huge timesaver!"</p>
                        </blockquote>
                        <figcaption class="flex items-center justify-center">
                            <img class="rounded-full w-9 h-9"
                                src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png"
                                alt="profile picture">
                            <div class="ms-3">
                                <div class="font-medium dark:text-white">Roberta Casas</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Lead Designer at Dropbox</div>
                            </div>
                        </figcaption>
                    </figure>
                </div>

                <!-- Item 3 -->
                <div class="p-8 px-20 mx-20 hidden duration-700 ease-in-out" data-carousel-item>
                    <figure
                        class="grid place-items-center p-8 text-center bg-white border rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 min-h-[300px] mx-auto min-w-[900px]">
                        <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mindblowing workflow</h3>
                            <p class="my-4">"Aesthetically, the well-designed components are beautiful and will
                                undoubtedly level up your next application."</p>
                        </blockquote>
                        <figcaption class="flex items-center justify-center">
                            <img class="rounded-full w-9 h-9"
                                src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png"
                                alt="profile picture">
                            <div class="ms-3">
                                <div class="font-medium dark:text-white">Jese Leos</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Software Engineer at Facebook</div>
                            </div>
                        </figcaption>
                    </figure>
                </div>

                <!-- Item 4 -->
                <div class="p-8 px-20 mx-20 hidden duration-700 ease-in-out" data-carousel-item>
                    <figure
                        class="grid place-items-center p-8 text-center bg-white border rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 min-h-[300px] min-w-[900px] mx-auto">
                        <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Efficient Collaborating</h3>
                            <p class="my-4">"You have many examples that can be used to create a fast prototype for your
                                team."</p>
                        </blockquote>
                        <figcaption class="flex items-center justify-center">
                            <img class="rounded-full w-9 h-9"
                                src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/joseph-mcfall.png"
                                alt="profile picture">
                            <div class="ms-3">
                                <div class="font-medium dark:text-white">Joseph McFall</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">CTO at Google</div>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            </div>
            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
                <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1"
                    data-carousel-slide-to="0"></button>
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2"
                    data-carousel-slide-to="1"></button>
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3"
                    data-carousel-slide-to="2"></button>
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4"
                    data-carousel-slide-to="3"></button>
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5"
                    data-carousel-slide-to="4"></button>
            </div>
            <!-- Slider controls -->
            <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 1 1 5l4 4" />
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
        </div>
        <div class="items-center justify-center mx-auto">
            <button type="button" {{ route('history') }}
                class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Lihat lebih banyak</button>
        </div>
    </div> --}}
@endsection
