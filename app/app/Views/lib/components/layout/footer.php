<?php use App\Helpers\NavigationHelper; ?>
<footer class="bg-white w-full" aria-labelledby="footer-heading">
  <h2 id="footer-heading" class="sr-only">Footer</h2>
  <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
    <div class="xl:grid xl:grid-cols-3 xl:gap-8">
      <div class="space-y-8">
        <img class="h-12" src="<?= base_url('assets/img/logo.png') ?>" alt="Unité scoute et guide de gosselies">
        <p class="text-sm leading-6 text-gray-600">Unité scoute et guide de gosselies.</p>
        <div class="flex space-x-6">
          <?php foreach ([['https://www.facebook.com/LesScoutsDeGosselieste003', 'Facebook des scouts', 'text-[#4A8FFF]'], ['https://www.facebook.com/GuidesGosselies', 'Facebook des guides', 'text-[deeppink]']] as [$href, $label, $color]): ?>
            <a href="<?= $href ?>" target="_blank" rel="noopener" class="hover:opacity-80">
              <span class="sr-only"><?= $label ?></span>
              <?= component('icon', ['name' => 'facebook', 'class' => 'h-6 w-6 ' . $color]) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- 2 columns on mobile, 4 from "md": the rows stay aligned whatever the number of links -->
      <div class="mt-16 grid grid-cols-2 gap-x-8 gap-y-10 md:grid-cols-4 xl:col-span-2 xl:mt-0">
        <?php foreach (NavigationHelper::FOOTER as $key): ?>
          <?php
            $menu = NavigationHelper::menu()[$key];
            // The first link (presentation) is followed by the sections of the unit
            $links = $menu['links'];
            if (!empty($menu['sections']))
              array_splice($links, 1, 0, [['href' => $menu['sections'][0]['href'], 'label' => 'Sections']]);
          ?>
          <div>
            <h3 class="text-sm font-semibold leading-6 text-gray-900"><?= esc($menu['footer']) ?></h3>
            <ul role="list" class="mt-6 space-y-4">
              <?php foreach ($links as $link): ?>
                <li>
                  <a href="<?= $link['href'] ?>" class="text-sm leading-6 text-gray-600 hover:text-gray-900"><?= esc($link['label']) ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 lg:mt-24">
      <p class="text-xs leading-5 text-gray-500">&copy;
        <?= date("Y"); ?> U.S.G.G. Tous droits réservés.
      </p>
    </div>
  </div>
</footer>
