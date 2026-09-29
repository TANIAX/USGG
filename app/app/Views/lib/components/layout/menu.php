<?php
// Desktop menu: one dropdown per menu of NavigationHelper::MENU ("opened" = key of the open dropdown)
use App\Helpers\NavigationHelper;
?>
<nav class="flex justify-around md:justify-start" x-data="{ opened: null }" @scroll.window.throttle="opened = null">
    <!-- LOGO -->
    <!-- shrink-0: the logo keeps its proportions, it is smaller on tablets to leave room for the menu -->
    <div class="relative hidden shrink-0 md:block md:mr-8 lg:ml-32 lg:mr-24">
        <a href="<?= base_url() ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="h-20 w-auto max-w-none lg:h-32">
        </a>
    </div>

    <div class="container mx-auto flex">
        <?php foreach (NavigationHelper::menu() as $key => $menu): ?>
            <div class="relative flex">
                <button type="button" @click="opened = opened === '<?= $key ?>' ? null : '<?= $key ?>'" @click.outside="if (opened === '<?= $key ?>') opened = null"
                    :aria-expanded="(opened === '<?= $key ?>').toString()" aria-controls="menu-<?= $key ?>"
                    class="text-gray-500 group p-4 inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900">
                    <span :class="{ 'underline': opened === '<?= $key ?>' }" class="underline-offset-4 decoration-2 decoration-blue-800 uppercase"><?= esc($menu['label']) ?></span>
                    <?= component('icon', ['name' => 'chevron-down', 'class' => 'text-gray-400 ml-2 h-5 w-5 group-hover:text-gray-500 duration-300', 'attrs' => [':class' => "{ 'rotate-180': opened === '$key' }"]]) ?>
                </button>

                <div id="menu-<?= $key ?>" x-show="opened === '<?= $key ?>'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90" class="fixed left-0 z-50 mt-32 w-screen">
                    <div class="relative isolate z-50 shadow">
                        <div class="absolute inset-x-0 top-0 -z-10 bg-slate-100 pt-2 shadow-lg ring-1 ring-gray-900/5">
                            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-6 py-10 lg:grid-cols-2 lg:px-8">
                                <div class="grid grid-cols-2 gap-x-6 sm:gap-x-8">
                                    <div>
                                        <h3 class="text-sm font-medium leading-6 text-gray-500">Rubrique</h3>
                                        <div class="mt-6 flow-root">
                                            <div class="-my-2">
                                                <?php foreach ($menu['links'] as $link): ?>
                                                    <a href="<?= $link['href'] ?>" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                        <?= component('icon', ['name' => $link['icon'], 'class' => 'h-6 w-6 flex-none text-gray-400']) ?>
                                                        <?= esc($link['label']) ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($menu['sections'])): ?>
                                        <div>
                                            <h3 class="text-sm font-medium leading-6 text-gray-500">Sections</h3>
                                            <div class="mt-6 flow-root">
                                                <div class="-my-2">
                                                    <?php foreach ($menu['sections'] as $section): ?>
                                                        <a href="<?= $section['href'] ?>" class="flex gap-x-4 py-2 text-sm font-semibold leading-6 text-gray-900">
                                                            <img class="h-6 w-6 flex-none rounded-full bg-white object-contain" src="<?= base_url($section['logo']) ?>" alt="">
                                                            <?= esc($section['name']) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</nav>
