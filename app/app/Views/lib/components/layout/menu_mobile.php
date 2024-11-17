
<div x-data="{
    open_guide: false,
    open_scout: false,
    open_asbl: false,
    open_en_pratique: false,
}" class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 fixed min-h-screen min-w-[50%] z-50 top-0">
    <div class="flex h-16 shrink-0 justify-between items-center">
        <img class="h-12 w-auto" src="<?= base_url('assets/img/logo.png') ?>" alt="logo USGG">

        <div class="flex py-2.5">
            <div class="sm:w-full cursor-pointer md:hidden" @click="open = !open">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black"
                    class="w-6 h-6" x-bind:class="open ? 'hidden' : ''">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black"
                    class="w-6 h-6" x-bind:class="open ? '' : 'hidden'">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Accueil -->
                    <li>
                        <a href="/" class="block rounded-md py-2 pr-2 pl-10 text-sm leading-6 font-semibold text-gray-700 hover:bg-gray-50">Accueil</a>
                    </li>

                    <!-- Guides -->
                    <li>
                        <div @click="open_guide = !open_guide;">
                            <button type="button" class="hover:bg-gray-50 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700" aria-controls="sub-menu-1" aria-expanded="false">
                                <svg class="h-5 w-5 shrink-0 transition-all" x-bind:class="open_guide ? 'rotate-90 text-gray-500' : 'text-gray-400'" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                                Unité guide
                            </button>
                            <ul class="mt-1 px-2" id="sub-menu-1" x-show="open_guide">
                                <li>
                                    <a href="/guide" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Présentation des sections</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Staff d'unité</a>
                                </li>
                                <li>
                                    <a href="/guide/document" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Documents</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Galerie photos</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Scouts -->
                    <li>
                        <div @click="open_scout = !open_scout;">
                            <button type="button" class="hover:bg-gray-50 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700" aria-controls="sub-menu-1" aria-expanded="false">
                                <svg class="h-5 w-5 shrink-0 transition-all" x-bind:class="open_scout ? 'rotate-90 text-gray-500' : 'text-gray-400'" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                                Unité scoute
                            </button>
                            <ul class="mt-1 px-2" id="sub-menu-1" x-show="open_scout">
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Présentation des sections</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Staff d'unité</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Documents</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Galerie photos</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- ASBL -->
                    <li>
                        <div @click="open_asbl = !open_asbl;">
                            <button type="button" class="hover:bg-gray-50 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700" aria-controls="sub-menu-1" aria-expanded="false">
                                <svg class="h-5 w-5 shrink-0 transition-all" x-bind:class="open_asbl ? 'rotate-90 text-gray-500' : 'text-gray-400'" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                                ASBL
                            </button>
                            <ul class="mt-1 px-2" id="sub-menu-1" x-show="open_asbl">
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Présentation</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Évenements</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- En pratique -->
                    <li>
                        <div @click="open_en_pratique = !open_en_pratique;">
                            <button type="button" class="hover:bg-gray-50 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700" aria-controls="sub-menu-1" aria-expanded="false">
                                <svg class="h-5 w-5 shrink-0 transition-all" x-bind:class="open_en_pratique ? 'rotate-90 text-gray-500' : 'text-gray-400'" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                                En pratique
                            </button>
                            <ul class="mt-1 px-2" id="sub-menu-1" x-show="open_en_pratique">
                                <li>
                                    <a href="/en-pratique/inscription" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Inscription</a>
                                </li>
                                <li>
                                    <a href="/en-pratique/cotisation" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Cotisation</a>
                                </li>
                                <li>
                                    <a href="/en-pratique/agenda" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700">Agenda</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>