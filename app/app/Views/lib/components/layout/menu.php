<nav class="flex justify-around	md:justify-start"
    x-data="{ open_guide: false, open_scout: false, open_asbl: false, open_en_pratique : false }">
    <!-- LOGO -->
    <div class="relative flex hidden md:block lg:ml-32 md:mr-24">
        <a href="<?= base_url() ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="min-h-32 h-32">
        </a>
    </div>

    <!-- GUIDES -->
    <div class="container mx-auto flex">
        <div class="relative flex">
            <button @scroll.window.throttle="open_guide = false"
                @click="open_guide = ! open_guide; open_scout = false ; open_asbl = false; open_en_pratique = false"
                type="button" @click.outside="open_guide = false;"
                class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900"
                aria-expanded="false">
                <span :class="{'underline': open_guide}"
                    class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">Unité guide</span>
                <svg :class="{'rotate-180 duration-300': open_guide, 'duration-300' : !open_guide}"
                    class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open_guide" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">
                <div class="relative isolate z-50 shadow">
                    <div class="absolute inset-x-0 top-0 -z-10 bg-slate-100 pt-2 shadow-lg ring-1 ring-gray-900/5">
                        <div
                            class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-6 py-10 lg:grid-cols-2 lg:px-8">
                            <div class="grid grid-cols-2 gap-x-6 sm:gap-x-8">
                                <div>
                                    <h3 class="text-sm font-medium leading-6 text-gray-500">Rubrique</h3>
                                    <div class="mt-6 flow-root">
                                        <div class="-my-2">
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                                                   </svg>
                                                Présentation  
                                            </a>
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                </svg>
                                                Staff
                                            </a>

                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                Documents
                                            </a>
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                Galeries photos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium leading-6 text-gray-500">Sections</h3>
                                    <div class="mt-6 flow-root">
                                        <div class="-my-2">
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                            <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-nutons.png') ?>" alt="Nutons">
                                                Nuton
                                            </a>
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-lutins.png') ?>" alt="Lutins">
                                                Lutin
                                            </a>
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-aventures.png') ?>" alt="Aventures">
                                                Aventure
                                            </a>
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-horizons.png') ?>" alt="Horizons">
                                                Horizon
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- SCOUTS -->
        <div class="relative flex">
            <button @scroll.window.throttle="open_scout = false"
                @click="open_scout = ! open_scout; open_guide = false; open_asbl = false; open_en_pratique = false"
                @click.outside="open_scout = false;" type="button"
                class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900"
                aria-expanded="false">
                <span :class="{'underline': open_scout}"
                    class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">Unité scoute</span>
                <svg :class="{'rotate-180 duration-300': open_scout, 'duration-300' : !open_scout}"
                    class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open_scout" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">

                <div class="relative isolate z-50 shadow">
                    <div class="absolute inset-x-0 top-0 -z-10 bg-slate-100 pt-2 shadow-lg ring-1 ring-gray-900/5">
                        <div
                            class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-6 py-10 lg:grid-cols-2 lg:px-8">
                            <div class="grid grid-cols-2 gap-x-6 sm:gap-x-8">
                                <div>
                                    <h3 class="text-sm font-medium leading-6 text-gray-500">Rubrique</h3>
                                    <div class="mt-6 flow-root">
                                        <div class="-my-2">
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                                                   </svg>
                                                Présentation  
                                            </a>
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                </svg>
                                                Staff
                                            </a>

                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                Documents
                                            </a>
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                Galeries photos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium leading-6 text-gray-500">Sections</h3>
                                    <div class="mt-6 flow-root">
                                        <div class="-my-2">
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                            <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-baladins.png') ?>" alt="Baladins">
                                                Baladin
                                            </a>
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-louveteaux.png') ?>" alt="Louveteaux">
                                                Louveteau
                                            </a>
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-eclaireurs.png') ?>" alt="Éclaireurs">
                                                Éclaireur
                                            </a>
                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <img class="h-6 w-6 flex-none text-gray-400 rounded-full" src="<?= base_url('assets/img/logo-pionniers.png') ?>" alt="Pionniers">
                                                Pionnier
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ASBL -->
        <div class="relative flex">
            <button @scroll.window.throttle="open_asbl = false"
                @click="open_asbl = ! open_asbl; open_guide = false; open_scout = false; open_en_pratique = false"
                @click.outside="open_asbl = false;" type="button"
                class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900"
                aria-expanded="false">
                <span :class="{'underline': open_asbl}"
                    class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">ASBL</span>
                <svg :class="{'rotate-180 duration-300': open_asbl, 'duration-300' : !open_asbl}"
                    class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open_asbl" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">

                <div class="relative isolate z-50 shadow">
                    <div class="absolute inset-x-0 top-0 -z-10 bg-slate-100 pt-2 shadow-lg ring-1 ring-gray-900/5">
                        <div
                            class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-6 py-10 lg:grid-cols-2 lg:px-8">
                            <div class="grid grid-cols-2 gap-x-6 sm:gap-x-8">
                                <div>
                                    <h3 class="text-sm font-medium leading-6 text-gray-500">Rubrique</h3>
                                    <div class="mt-6 flow-root">
                                        <div class="-my-2">
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                                                   </svg>
                                                Présentation  
                                            </a>

                                            <a href="#" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                                </svg>
                                                Évenements
                                            </a>
                                            <a href="#"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                                    </svg>
                                                Contact
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- En pratique -->
        <div class="relative flex">
            <button @scroll.window.throttle="open_en_pratique = false"
                @click="open_en_pratique = ! open_en_pratique; open_guide = false; open_scout = false; open_asbl = false"
                @click.outside="open_en_pratique = false;" type="button"
                class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900"
                aria-expanded="false">
                <span :class="{'underline': open_en_pratique}"
                    class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">En pratique</span>
                <svg :class="{'rotate-180 duration-300': open_en_pratique, 'duration-300' : !open_en_pratique}"
                    class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open_en_pratique" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">

                <div class="relative isolate z-50 shadow">
                    <div class="absolute inset-x-0 top-0 -z-10 bg-slate-100 pt-2 shadow-lg ring-1 ring-gray-900/5">
                        <div
                            class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-6 py-10 lg:grid-cols-2 lg:px-8">
                            <div class="grid grid-cols-2 gap-x-6 sm:gap-x-8">
                                <div>
                                    <h3 class="text-sm font-medium leading-6 text-gray-500">Rubrique</h3>
                                    <div class="mt-6 flow-root">
                                        <div class="-my-2">
                                            <a href="/en-pratique/inscription"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                                   </svg>
                                                Inscription  
                                            </a>

                                            <a href="/en-pratique/cotisation" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                                </svg>
                                                Cotisation
                                            </a>
                                            <a href="/en-pratique/agenda"
                                                class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                <svg class="h-6 w-6 flex-none text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                                </svg>
                                                Agenda
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</nav>