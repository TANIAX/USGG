<?php
// Mobile menu (inside the x-data of the header: "open" closes it)
use App\Helpers\NavigationHelper;
?>
<div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 fixed min-h-screen min-w-[50%] z-50 top-0">
    <div class="flex h-16 shrink-0 justify-between items-center">
        <img class="h-12 w-auto" src="<?= base_url('assets/img/logo.png') ?>" alt="logo USGG">

        <div class="flex py-2.5">
            <button type="button" class="sm:w-full cursor-pointer md:hidden" @click="open = false" aria-label="Fermer le menu">
                <?= component('icon', ['name' => 'x-mark', 'class' => 'w-6 h-6 text-black']) ?>
            </button>
        </div>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="/" class="block rounded-md py-2 pr-2 pl-10 text-sm leading-6 font-semibold text-gray-700 hover:bg-gray-50">Accueil</a>
                    </li>

                    <?php foreach (NavigationHelper::menu() as $key => $menu): ?>
                        <li x-data="{ expanded: false }">
                            <button type="button" @click="expanded = !expanded" :aria-expanded="expanded.toString()" aria-controls="sub-menu-<?= $key ?>"
                                class="hover:bg-gray-50 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700">
                                <?= component('icon', ['name' => 'chevron-right', 'class' => 'h-5 w-5 shrink-0 transition-all', 'attrs' => [':class' => "expanded ? 'rotate-90 text-gray-500' : 'text-gray-400'"]]) ?>
                                <?= esc($menu['label']) ?>
                            </button>
                            <ul class="mt-1 px-2" id="sub-menu-<?= $key ?>" x-show="expanded">
                                <?php foreach ($menu['links'] as $link): ?>
                                    <li>
                                        <a href="<?= $link['href'] ?>" class="hover:bg-gray-50 block rounded-md py-2 pr-2 pl-9 text-sm leading-6 text-gray-700"><?= esc($link['label']) ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </nav>
</div>
