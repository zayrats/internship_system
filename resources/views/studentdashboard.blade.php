@extends('master')
@section('content')
    <div
        class="flex flex-col items-center bg-white border border-gray-200  shadow-sm md:flex-row  dark:border-gray-700 dark:bg-gray-800 ">
        <div class="flex flex-col justify-between p-4 leading-normal pt-16 pb-10 px-10">
            <h3 class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">Your Story
                become
                my apprentice</h3>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Discover comprehensive internship information
                from senior peers, explore their success stories, and directly connect with them through provided
                contacts on GoShip.</p>
            <a href="{{ route('internship') }}"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Knowing Your Intern
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </a>
        </div>
        <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-54 md:rounded-none md:rounded-s-lg"
            src="../image/internship.jpg" alt="internship">
    </div>


    <div
        class="flex flex-col items-center bg-white border border-gray-200 h-60 shadow-sm md:flex-row md:max-w-full hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:hover:bg-gray-700">
        <!-- Bungkus gambar dengan <a> -->
        <a href="{{ route('maps') }}" class="group">
            <img class="mx-20 object-cover w-full rounded-t-lg md:h-auto md:w-60 md:rounded-none md:rounded-s-lg transition-transform duration-300 ease-in-out transform group-hover:scale-110"
                src="http://2.bp.blogspot.com/-zsKt2Xv7pb0/UV2PedQ4tgI/AAAAAAAAACY/09wKfwHFdSM/s1600/peta+perekonomian.gif"
                alt="maps image">
        </a>

        <!-- Bagian teks tetap tanpa link -->
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">Internship on Indonesia</h5>
            <p class="mb-3 text-xl font-normal text-gray-700 dark:text-gray-400">See students who have done internships
                throughout Indonesia</p>
        </div>
    </div>


    {{-- menampilkan chat grup --}}
    <div
        class="flex flex-col  bg-white border border-gray-200 h-auto  shadow-sm md:flex-col md:max-w-full  dark:border-gray-700 dark:bg-gray-600  ">
        <h3 class="pt-5 mb-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white text-center">Discussion Forum
        </h3>
        <div class="flex items-start gap-2.5 mx-20">
            <div class="flex flex-col gap-1 w-full max-w-[320px]">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Bonnie Green</span>
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">11:46</span>
                </div>
                <div
                    class="flex flex-col leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl dark:bg-gray-700">
                    <p class="text-sm font-normal text-gray-900 dark:text-white"> That's awesome. I think our users will
                        really appreciate the improvements.</p>
                </div>
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Delivered</span>
            </div>

        </div>
        <div class="pt-4 flex flex-col gap-2.5 mx-20">

            <form>
                <label for="chat" class="sr-only">Your message</label>
                <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                    <button type="button"
                        class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 18">
                            <path fill="currentColor"
                                d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 1H2a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z" />
                        </svg>
                        <span class="sr-only">Upload image</span>
                    </button>
                    <button type="button"
                        class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z" />
                        </svg>
                        <span class="sr-only">Add emoji</span>
                    </button>
                    <textarea id="chat" rows="1"
                        class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Your message..."></textarea>
                    <button type="submit"
                        class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600">
                        <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 18 20">
                            <path
                                d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z" />
                        </svg>
                        <span class="sr-only">Send message</span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div
        class="flex flex-col  bg-white border border-gray-200 h-auto  shadow-sm md:flex-col md:max-w-full  dark:border-gray-700 dark:bg-gray-500  ">
        <h3 class="pt-5 mb-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
            Student Experience
        </h3>

        <div id="indicators-carousel" class="relative w-full" data-carousel="static">
            <!-- Carousel wrapper -->
            <div id="testimonial-carousel" class="relative h-auto overflow-hidden rounded-lg md:h-96 mx-20 items-center justify-center ">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out p-8  pl-60" data-carousel-item="active">
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
                <div class="p-8 pl-60 hidden duration-700 ease-in-out" data-carousel-item>
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
                <div class="p-8 pl-60 hidden duration-700 ease-in-out" data-carousel-item>
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
                <div class="p-8 pl-60 hidden duration-700 ease-in-out" data-carousel-item>
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
                class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">See
                More</button>
        </div>
    </div>
@endsection
