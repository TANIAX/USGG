<nav class="flex justify-around	md:justify-start" x-data="{ open_guide: false, open_scout: false, open_asbl: false}">
    <!-- LOGO -->
    <div class="relative flex hidden md:block md:ml-32 md:mr-24">
        <a href="<?= base_url() ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="min-h-32 h-32">
        </a>
    </div>

    <!-- SCOUTS -->
    <div class="relative flex">
        <button @scroll.window.throttle="open_scout = false" @click="open_scout = ! open_scout; open_guide = false; open_asbl = false" @click.outside="open_scout = false;" type="button" class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900" aria-expanded="false">
            <span :class="{'underline': open_scout}" class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">Unité scout</span>
            <svg :class="{'rotate-180 duration-300': open_scout, 'duration-300' : !open_scout}" class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open_scout" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">

            <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="relative grid gap-6 bg-slate-100 px-5 py-6 sm:gap-8 sm:p-8">

                    <ul>
                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Présentation</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Sections</p>
                                    <div class="ml-4">
                                        <p class="mt-1 text-sm text-gray-500">Baladin</p>
                                        <p class="mt-1 text-sm text-gray-500">Louveteau</p>
                                        <p class="mt-1 text-sm text-gray-500">Éclaireur</p>
                                        <p class="mt-1 text-sm text-gray-500">Pionner</p>
                                    </div>

                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Staff d'unité</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Documents</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Galeries photos</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

        <!-- GUIDES -->
    <div class="relative flex">
        <button @scroll.window.throttle="open_guide = false"  @click="open_guide = ! open_guide; open_scout = false ; open_asbl = false" type="button" @click.outside="open_guide = false;" class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900" aria-expanded="false">
            <span :class="{'underline': open_guide}" class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">Unité guide</span>
            <svg :class="{'rotate-180 duration-300': open_guide, 'duration-300' : !open_guide}" class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open_guide" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">
            <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="relative grid gap-6 bg-slate-100 px-5 py-6 sm:gap-8 sm:p-8">
                    <ul>
                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Présentation</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Sections</p>
                                    <div class="ml-4">
                                        <p class="mt-1 text-sm text-gray-500">Nuton</p>
                                        <p class="mt-1 text-sm text-gray-500">Lutin</p>
                                        <p class="mt-1 text-sm text-gray-500">Aventure</p>
                                        <p class="mt-1 text-sm text-gray-500">Horizon</p>
                                    </div>

                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Staff d'unité</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Documents</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Galeries photos</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ASBL -->
    <div class="relative flex">
        <button @scroll.window.throttle="open_asbl = false" @click="open_asbl = ! open_asbl; open_guide = false; open_scout = false" @click.outside="open_asbl = false;" type="button" class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900" aria-expanded="false">
            <span :class="{'underline': open_asbl}" class="underline-offset-4 decoration-2 decoration-blue-800 uppercase ">ASBL</span>
            <svg :class="{'rotate-180 duration-300': open_asbl, 'duration-300' : !open_asbl}" class="text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open_asbl" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="fixed left-0  z-50 mt-32 w-screen">

            <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="relative grid gap-6 bg-slate-100 px-5 py-6 sm:gap-8 sm:p-8">

                    <ul>
                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Présentation</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Évenements</p>
                                </div>
                            </a>
                        </li>

                        <li class="py-4 pl-2 md:pl-64 hover:bg-gray-50">
                            <a href="#" class="-m-3 flex items-start rounded-lg p-3">
                                <svg class="h-2 w-2 mt-2 flex-shrink-0 text-template-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-2 h-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">Contacts</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>