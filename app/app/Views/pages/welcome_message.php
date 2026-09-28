<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
  Guides et scoutes de Gosselies
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Banner -->
<header class="banner hidden md:block">
  <span class="background"></span>
  <div class="animate__animated animate__slideInUp animate__slow">
    <h1>UNITÉ GUIDE ET SCOUT DE GOSSELIES</h1>
  </div>
</header>

<!-- Présentation -->
<article class="bg-white">
  <div class="relative isolate overflow-hidden bg-gradient-to-b from-indigo-100/20 sm:pt-4 md:pt-14">
    <div class="absolute inset-y-0 right-1/2 -z-10 -mr-96 w-[200%] origin-top-right skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:-mr-80 lg:-mr-96" aria-hidden="true"></div>
    <div class="mx-auto max-w-7xl px-6 py-8 sm:py-40 lg:px-8">
      <div class="mx-auto max-w-2xl lg:mx-0 lg:grid lg:max-w-none lg:grid-cols-2 lg:gap-x-16 lg:gap-y-6 xl:grid-cols-1 xl:grid-rows-1 xl:gap-x-8">
        <h1 class="max-w-2xl text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl lg:col-span-2 xl:col-auto">Le scoutisme à Gosselies.</h1>
        <div class="mt-6 max-w-xl lg:mt-0 xl:col-end-1 xl:row-start-1">
          <p class="text-lg leading-8 text-gray-600 py-4">Implantée à <span class="font-bold">Gosselies</span> depuis 80 ans, notre unité scoute compte plus de 300 membres dont environ 50 animateurs, soit plus de
            200 familles de charleroi répartis en 8 sections, épaulées par un staff d'Unité composé de parents.</p>
          <p class="text-lg leading-8 text-gray-600 py-4"> L'Unité est membre de la Fédération Les Scouts asbl qui
            regroupe plus de 50.000 enfants et chefs en Belgique. <br>Notre énergie pour suivre la piste scoute n'a d'égale que la joie de nos enfants de grandir en la
            découvrant.</p>
          <p class="text-lg leading-8 text-gray-600 py-4">scoutisme, c'est géant.</p>
          <div class="mt-10 flex items-center gap-x-6">
            <a href="#faq" class="text-sm font-semibold leading-6 text-gray-900">En savoir plus <span aria-hidden="true">→</span></a>
          </div>
        </div>
        <img src="<?= base_url('assets/img/scoutisme-banner.png') ?>" alt="le scoutisme cover" class="mt-10 aspect-[6/5] w-full max-w-lg rounded-2xl object-cover sm:mt-16 lg:mt-0 lg:max-w-none xl:row-span-2 xl:row-end-2 xl:mt-36">
      </div>
    </div>
    <div class="absolute inset-x-0 bottom-0 -z-10 h-24 bg-gradient-to-t from-white sm:h-32"></div>
  </div>
</article>

<!-- Timeline -->
<article class="bg-slate-100 pt-12">
  <div class="container mx-auto max-w-[1000px]">
    <div class="hidden xl:block w-24 mt-[730px] ml-[-100px] absolute">
      <h1 class="-rotate-90 uppercase text-6xl font-bold p-2">Garçons</h1>
    </div>
    <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-6">
      <div class="md:col-span-3 p-4 reveal-reverse">
        <img src="<?= base_url('assets/img/logo-scout.png') ?>" class="p-4 mx-auto max-w-[500px] max-h-[200px] object-contain w-[90vw] md:w-auto" alt="logo scouts">

        <div class="flex justify-center md:justify-normal md:flex-row-reverse w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-baladins.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom rotate-4 shadow-md" alt="logo baladins">
          </div>
          <div class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">6 à 8 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Baladins</span>
          </div>
        </div>

        <hr>

        <div class="flex justify-center md:justify-normal md:flex-row-reverse w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-louveteaux.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom rotate-4 shadow-md" alt="logo louvetaux">
          </div>
          <div class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">8 à 12 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Louvetaux</span>
          </div>
        </div>

        <hr>

        <div class="flex justify-center md:justify-normal md:flex-row-reverse w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-eclaireurs.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom rotate-4 shadow-md" alt="logo éclaireurs">
          </div>
          <div class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">12 à 16 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Éclaireurs</span>
          </div>
        </div>

        <hr>

        <div class="flex justify-center md:justify-normal md:flex-row-reverse w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-pionniers.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom rotate-4 shadow-md" alt="logo pionners">
          </div>
          <div class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">16 à 18 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Pionners</span>
          </div>
        </div>
      </div>

      <div class="hidden xl:block w-24 mt-[577px] ml-[1000px] absolute">
        <h1 class="rotate-90 uppercase text-6xl font-bold p-2">Filles</h1>
      </div>
      <div class="md:col-span-3 reveal">
        <img src="<?= base_url('assets/img/logo-guide.png') ?>" class="p-4 mx-auto max-w-[500px] max-h-[200px] object-contain mt-4" alt="logo guide">

        <div class="flex justify-center md:justify-normal flex-row-reverse md:flex-row w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-nutons.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom -rotate-4 shadow-md" alt="logo nuton">
          </div>
          <a href="/guide/#nutons" class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">5 à 7 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Nutons</span>
          </a>
        </div>

        <hr>

        <div class="flex justify-center md:justify-normal flex-row-reverse md:flex-row w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-lutins.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom -rotate-4 shadow-md" alt="logo lutin">
          </div>
          <a href="/guide/#lutins" class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">8 à 11 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">lutins</span>
          </a>
        </div>

        <hr>

        <div class="flex justify-center md:justify-normal flex-row-reverse md:flex-row w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-aventures.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom -rotate-4 shadow-md" alt="logo aventures">
          </div>
          <a href="/guide/#aventures" class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">11 à 15 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Aventures</span>
          </a>
        </div>

        <hr>

        <div class="flex justify-center md:justify-normal flex-row-reverse md:flex-row w-full py-8">
          <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
            <img src="<?= base_url('assets/img/logo-horizons.png') ?>" class="w-28 h-28 bg-white object-contain origin-bottom -rotate-4 shadow-md" alt="logo horizons">
          </div>
          <a href="/guide/#horizons" class="flex flex-col items-center justify-center p-8 magnetic">
            <label class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600">16 à 18 ans</label>
            <span class="font-bold text-gray-500 text-2xl tracking-tigh italic uppercase">Horizons</span>
          </a>
        </div>
      </div>
    </div>

  </div>

</article>



<!-- News: the upcoming events of the agenda, loaded 3 by 3 -->
<article id="actualites" class="bg-white py-8 sm:py-12" x-data="newsList(<?= esc($news, 'attr') ?>)">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Actualités</h2>
      <p class="mt-2 text-lg leading-8 text-gray-600">Prochainement chez les scouts et guides de <span class="font-bold">Gosselies</span>.</p>
    </div>
    <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
      <template x-for="item in items" :key="item.id">
        <article class="flex flex-col items-start">
          <a :href="`/actualites/${item.id}`" class="relative block w-full overflow-hidden rounded-2xl bg-gray-100 ring-1 ring-inset ring-gray-900/10">
            <template x-if="item.image_small_url">
              <img :src="item.image_small_url" :alt="item.title" loading="lazy" class="aspect-[16/9] w-full object-cover transition duration-300 hover:scale-105">
            </template>
            <!-- Without image: logo of the first section on its colour -->
            <template x-if="!item.image_small_url">
              <div class="flex aspect-[16/9] w-full items-center justify-center" :style="`background-color: ${(item.sections[0] || {}).color || '#6366f1'}1a`">
                <img x-show="item.sections.length" :src="item.sections.length ? baseUrl + item.sections[0].logo : ''" alt="" loading="lazy"
                  class="h-2/3 w-auto rounded-2xl bg-white object-contain p-2 shadow">
              </div>
            </template>
          </a>
          <div class="mt-6 flex flex-wrap items-center gap-2 text-xs">
            <time class="text-gray-500" :datetime="item.start_at" x-text="shortDate(item)"></time>
            <template x-for="section in item.sections" :key="section.id">
              <span class="inline-flex items-center gap-x-1.5 rounded-full bg-gray-50 px-2.5 py-1 font-medium text-gray-600">
                <span class="h-1.5 w-1.5 rounded-full" :style="`background-color: ${section.color}`"></span>
                <span x-text="section.name"></span>
              </span>
            </template>
          </div>
          <div class="group relative">
            <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
              <a :href="`/actualites/${item.id}`">
                <span class="absolute inset-0"></span>
                <span x-text="item.title"></span>
              </a>
            </h3>
            <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600" x-text="item.description || item.location || 'Plus d\'informations bientôt.'"></p>
          </div>
          <div class="relative mt-6 flex items-center gap-x-3">
            <img :src="item.author.picture_url || baseUrl + 'assets/img/logo-unite.png'" alt=""
              class="h-10 w-10 rounded-full bg-white ring-1 ring-gray-200" :class="item.author.picture_url ? 'object-cover' : 'object-contain p-1'">
            <div class="text-sm leading-6">
              <p class="text-gray-500">Publié par</p>
              <p class="font-semibold text-gray-900" x-text="item.author.name"></p>
            </div>
          </div>
        </article>
      </template>
    </div>

    <div x-show="items.length === 0" class="mx-auto mt-12 max-w-xl text-center text-gray-500">
      Aucune activité n'est annoncée pour le moment. Consultez l'<a href="/en-pratique/agenda" class="link">agenda</a> pour la suite.
    </div>
  </div>

  <!-- More news: hidden when there are no more -->
  <div class="flex flex-col items-center justify-center py-12 sm:py-16">
    <button type="button" x-show="hasMore" @click="loadMore()" :disabled="loading"
      class="rounded-md px-6 py-2.5 text-sm font-semibold uppercase tracking-widest text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-100 hover:bg-indigo-50 disabled:opacity-50"
      x-text="loading ? 'Chargement…' : 'Voir plus d\'actualités'"></button>
    <p x-show="error" class="mt-2 text-sm text-red-600" x-text="error"></p>
    <a x-show="!hasMore && items.length > 0" href="/en-pratique/agenda" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Voir tout l'agenda</a>
  </div>
</article>

<script>
  function newsList(initial) {
    return {
      baseUrl: '<?= rtrim(base_url(), '/') ?>/',
      items: initial.items,
      hasMore: initial.has_more,
      loading: false,
      error: '',

      async loadMore() {
        this.loading = true;
        this.error = '';
        try {
          const response = await fetch(`${this.baseUrl}api/v1/actualites?offset=${this.items.length}&limit=3`, { headers: { 'Accept': 'application/json' } });
          const json = await response.json();
          if (!response.ok || !json.success)
            throw new Error();
          // An event may already be displayed if the list changed in the meantime
          const known = this.items.map(item => item.id);
          this.items.push(...json.data.items.filter(item => !known.includes(item.id)));
          this.hasMore = json.data.has_more;
        } catch (error) {
          this.error = 'Impossible de charger les actualités suivantes. Réessayez plus tard.';
        } finally {
          this.loading = false;
        }
      },

      // e.g. "sam. 3 oct. 2026"
      shortDate(item) {
        return parseEventDate(item.start_at).toLocaleDateString('fr-BE', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
      },
    };
  }
</script>



<!-- Team -->
<article class="bg-slate-100 py-24 sm:py-32">
  <div class="mx-auto max-w-7xl">
    <div id="team_header" class="mx-auto px-6 lg:px-8 animate__animated animate__slow">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Portrait des responsables de section</h2>
      <!-- <p class="mt-6 text-lg leading-8 text-gray-600">Les unités possèdes chacuns leurs propres responsables.</p> -->
    </div>
    <ul id="team_list" role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-2 gap-x-8 gap-y-16 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6 reveal">
      <?php if (!empty($users) && is_array($users)) : ?>
        <?php foreach ($users as $user) : ?>
          <li>
            <img class="mx-auto h-24 w-24 rounded-full object-cover" src="<?= $user->picture ?>" alt="">
            <h3 class="mt-6 text-base font-semibold leading-7 tracking-tight text-gray-900">
              <?= $user->totem ?>
            </h3>
            <p class="text-sm font-semibold leading-6 text-indigo-600">
              <?= $user->user_type ?>
            </p>
          </li>
        <?php endforeach ?>
      <?php else : ?>
        <li>
          <p class="text-sm font-semibold leading-6 text-indigo-600">Aucun responsable pour le moment</p>
        </li>
      <?php endif ?>
    </ul>
  </div>
</article>
<!-- Testimonials -->
<div class="relative isolate overflow-hidden bg-white pb-32 pt-24 sm:pt-32" x-data="{ width : (window.innerWidth > 0) ? window.innerWidth : screen.width }"  @resize.window="width = (window.innerWidth > 0) ? window.innerWidth : screen.width;">
  <div class="absolute inset-x-0 top-1/2 -z-10 -translate-y-1/2 transform-gpu overflow-hidden opacity-30 blur-3xl" aria-hidden="true">
    <div class="ml-[max(50%,38rem)] aspect-[1313/771] w-[82.0625rem] bg-gradient-to-tr from-[#FCF7F8] to-[#CED3DC]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
  </div>
  <div class="absolute inset-x-0 top-0 -z-10 flex transform-gpu overflow-hidden pt-32 opacity-25 blur-3xl sm:pt-40 xl:justify-end" aria-hidden="true">
    <div class="ml-[-22rem] aspect-[1313/771] w-[82.0625rem] flex-none origin-top-right rotate-[30deg] bg-gradient-to-tr from-[#FCF7F8] to-[#CED3DC] xl:ml-0 xl:mr-[calc(50%-12rem)]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
  </div>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-xl text-center">
      <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Ils ont passés leurs enfances chez nous.</p>
    </div>
    <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 grid-rows-1 gap-8 text-sm leading-6 text-gray-900 sm:mt-20 sm:grid-cols-2 xl:mx-0 xl:max-w-none xl:grid-flow-col xl:grid-cols-4">
      <figure class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 sm:col-span-2 xl:col-start-2 xl:row-end-1 reveal">
        <blockquote class="p-6 text-lg font-semibold leading-7 tracking-tight text-gray-900 sm:p-12 sm:text-xl sm:leading-8">
          <p>”Mon enfance chez les guides a été une expérience enrichissante. Les activités en plein air m'ont appris la collaboration,le respect de la nature. Les souvenirs de feux de camp et d'aventures restent des moments forts de mon enfance, façonnant des valeurs qui perdurent.”</p>
        </blockquote>
        <figcaption class="flex flex-wrap items-center gap-x-4 gap-y-4 border-t border-gray-900/10 px-6 py-4 sm:flex-nowrap">
          <img class="h-10 w-10 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=1024&h=1024&q=80" alt="">
          <div class="flex-auto">
            <div class="font-semibold">Brenna Goyette</div>
            <div class="text-gray-600">@Koala</div>
          </div>
        </figcaption>
      </figure>
      <div class="space-y-8 xl:contents xl:space-y-0">
        <div class="space-y-8 xl:row-span-2">
          <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 reveal-left">
            <blockquote class="text-gray-900">
            <p>”Les guides ont été une expérience formidable. Les compétences en plein air, les amis et les valeurs positives ont marqué mon enfance de manière inoubliable.”</p>
          </blockquote>
            <figcaption class="mt-6 flex items-center gap-x-4">
              <img class="h-10 w-10 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
              <div>
                <div class="font-semibold">Leslie Alexander</div>
                <div class="text-gray-600">@Guanaco</div>
              </div>
            </figcaption>
          </figure>

        </div>
        <div class="space-y-8 xl:row-start-1">
          <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5" x-bind:class="width > 768 ? 'reveal-right' : 'reveal'">
            <blockquote class="text-gray-900">
              <p>“Être guide a été génial. Les aventures en plein air, les amitiés durables et les valeurs enseignées ont été des éléments clés de ma jeunesse.”</p>
            </blockquote>
            <figcaption class="mt-6 flex items-center gap-x-4">
              <img class="h-10 w-10 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
              <div>
                <div class="font-semibold">Lindsay Walton</div>
                <div class="text-gray-600">@Azara</div>
              </div>
            </figcaption>
          </figure>

        </div>
      </div>
      <div class="space-y-8 xl:contents xl:space-y-0">
        <div class="space-y-8 xl:row-start-1">
          <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 reveal">
            <blockquote class="text-gray-900">
              <p>“Les années chez les scouts ont été incroyables. Les leçons de vie, les amis proches et les souvenirs resteront toujours précieux.”</p>
            </blockquote>
            <figcaption class="mt-6 flex items-center gap-x-4">
              <img class="h-10 w-10 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
              <div>
                <div class="font-semibold">Tom Cook</div>
                <div class="text-gray-600">@zebre</div>
              </div>
            </figcaption>
          </figure>

        </div>
        <div class="space-y-8 xl:row-span-2">
          <figure class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5" x-bind:class="width > 768 ? 'reveal-right' : 'reveal'">
            <blockquote class="text-gray-900">
              <p>“Être scout a été une aventure enrichissante. Les activités pratiques, les amitiés solides et les valeurs positives ont laissé une empreinte durable.”</p>
            </blockquote>
            <figcaption class="mt-6 flex items-center gap-x-4">
              <img class="h-10 w-10 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
              <div>
                <div class="font-semibold">Leonard Krasner</div>
                <div class="text-gray-600">@ailurus</div>
              </div>
            </figcaption>
          </figure>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sign up newsletters -->
<div class="bg-slate-100 py-16 sm:py-24 lg:py-32">
  <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 lg:grid-cols-12 lg:gap-8 lg:px-8">
    <div class="max-w-xl text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl lg:col-span-7">
      <h2 class="inline sm:block lg:inline xl:block">Souhaitez-vous être notifiés lors de la parution d'une actualité?</h2>
      <p class="block sm:pt-8 md:pt-4">Inscrivez-vous à notre newsletter.</p>
    </div>
    <form class="w-full max-w-md lg:col-span-5 lg:pt-2">
      <div class="flex gap-x-4">
        <label for="email-address" class="sr-only">Adresse email</label>
        <input id="email-address" name="email" type="email" autocomplete="email" required class="min-w-0 flex-auto rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Entrez votre email">
        <button type="submit" class="flex-none rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Souscrire</button>
      </div>
      <p class="mt-4 text-sm leading-6 text-gray-900">Nous tenons compte de votre vie privée.<br>Lisez notre <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">politique de confidentialité</a>.</p>
    </form>
  </div>
</div>

<!-- FAQ -->
<article class="bg-white" id="faq">
  <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8 lg:py-40">
    <div class="mx-auto max-w-7xl divide-y divide-gray-900/10">
      <h2 class="text-2xl font-bold leading-10 tracking-tight text-gray-900">Questions fréquentes</h2>
      <dl class="mt-10 space-y-6 divide-y divide-gray-900/10">
        <div x-data="{ open: false }" class="pt-6">
          <dt>
            <button @click="open = !open" type="button" class="flex w-full items-start justify-between text-left text-gray-900" aria-controls="faq-0" aria-expanded="false">
              <span class="text-base font-semibold leading-7">Quels sont les avantages principaux de l'inscription de mon enfant achez les guide ou les scouts?</span>
              <span class="ml-6 flex h-7 items-center">
                <template x-if="!open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                  </svg>
                </template>
                <template x-if="open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                  </svg>
                </template>
              </span>
            </button>
          </dt>
          <dd x-show="open"  
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 transform scale-90"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-90"
              class="mt-2 pr-12" id="faq-0">
            <p class="text-base leading-7 text-gray-600">Les guide ou scouts offrent de nombreux avantages tels que le développement du leadership, l'apprentissage de compétences pratiques, la socialisation, la formation au travail d'équipe, et la connexion avec la nature. Les activités des guides et des scouts visent à favoriser la croissance personnelle et le sens des responsabilités.</p>
          </dd>
        </div>

        <div x-data="{ open: false }" class="pt-6">
          <dt>
            <button @click="open = !open" type="button" class="flex w-full items-start justify-between text-left text-gray-900" aria-controls="faq-0" aria-expanded="false">
              <span class="text-base font-semibold leading-7">Comment fonctionne la supervision et la sécurité lors des activités les guides et les scouts?</span>
              <span class="ml-6 flex h-7 items-center">
                <template x-if="!open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                  </svg>
                </template>
                <template x-if="open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                  </svg>
                </template>
              </span>
            </button>
          </dt>
          <dd x-show="open"  
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 transform scale-90"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-90"
              class="mt-2 pr-12" id="faq-0">
            <p class="text-base leading-7 text-gray-600">La sécurité des enfants est une priorité pour les guidew ainsi que les scouts. Les activités sont planifiées et supervisées par des adultes formés. Les camps et sorties sont organisés avec des protocoles de sécurité stricts, et les responsables sont généralement soumis à des vérifications d'antécédents.</p>
          </dd>
        </div>

        <div x-data="{ open: false }" class="pt-6">
          <dt>
            <button @click="open = !open" type="button" class="flex w-full items-start justify-between text-left text-gray-900" aria-controls="faq-0" aria-expanded="false">
              <span class="text-base font-semibold leading-7">Quel est l'engagement requis de la part des parents?</span>
              <span class="ml-6 flex h-7 items-center">
                <template x-if="!open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                  </svg>
                </template>
                <template x-if="open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                  </svg>
                </template>
              </span>
            </button>
          </dt>
          <dd x-show="open"  
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 transform scale-90"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-90"
              class="mt-2 pr-12" id="faq-0">
            <p class="text-base leading-7 text-gray-600">Les parents peuvent être impliqués de différentes manières, en fonction de leurs disponibilités. Certains peuvent devenir des bénévoles actifs, tandis que d'autres peuvent participer à des réunions ou événements ponctuels. Il est important de comprendre les attentes et de choisir un niveau d'engagement qui convient à la famille.</p>
          </dd>
        </div>

        <div x-data="{ open: false }" class="pt-6">
          <dt>
            <button @click="open = !open" type="button" class="flex w-full items-start justify-between text-left text-gray-900" aria-controls="faq-0" aria-expanded="false">
              <span class="text-base font-semibold leading-7">Comment les unités gèrent-elles l'inclusion et la diversité ?</span>
              <span class="ml-6 flex h-7 items-center">
                <template x-if="!open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                  </svg>
                </template>
                <template x-if="open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                  </svg>
                </template>
              </span>
            </button>
          </dt>
          <dd x-show="open"  
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 transform scale-90"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-90"
              class="mt-2 pr-12" id="faq-0">
            <p class="text-base leading-7 text-gray-600">Les guides et les scouts s'efforcent de promouvoir l'inclusion et la diversité. Ils accueillent des membres de toutes origines, croyances et sexes. Les activités sont conçues pour favoriser le respect mutuel et la compréhension interculturelle. Il peut être utile de discuter avec les responsables locaux pour comprendre comment ces principes sont mis en œuvre au sein du groupe.</p>
          </dd>
        </div>

        <div x-data="{ open: false }" class="pt-6">
          <dt>
            <button @click="open = !open" type="button" class="flex w-full items-start justify-between text-left text-gray-900" aria-controls="faq-0" aria-expanded="false">
              <span class="text-base font-semibold leading-7">Quels sont les coûts associés à l'adhésion aux unités guide / scoute ?</span>
              <span class="ml-6 flex h-7 items-center">
                <template x-if="!open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                  </svg>
                </template>
                <template x-if="open">
                  <svg class="h-6 w-6 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                  </svg>
                </template>
              </span>
            </button>
          </dt>
          <dd x-show="open"  
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 transform scale-90"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-90"
              class="mt-2 pr-12" id="faq-0">
            <p class="text-base leading-7 text-gray-600">Les coûts peuvent varier en fonction de la région et des activités spécifiques du groupe. Il est important de comprendre les frais d'adhésion, les coûts des uniformes, des camps et des événements spéciaux. De nombreuses organisations offrent des options d'aide financière pour assurer que la participation aux scouts soit accessible à tous.
            </dd>
        </div>
        <!-- More questions... -->
      </dl>
    </div>
  </div>
</article>

<style>
  body {
    margin: 0;
    opacity: 0;
    font: sofia;
  }


  /* Loaded body */
  body.loaded {
    opacity: 1;
    transition: 1s opacity;
  }

  /* Default banner */
  .banner {
    position: relative;
    width: 100%;
    height: 450px;
    padding: 0 5%;
    overflow: hidden;
    backface-visibility: hidden;
  }

  /* Default image container */
  .banner .background {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    z-index: -1;
    transform: translate3d(0, 0, 0) scale(1.25);
    background: black url(https://images.unsplash.com/photo-1556607437-b4b0417d2e0d?q=80&w=1889&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D) no-repeat center center;
    background-size: cover;
  }

  /* Loaded image container */
  .loaded .banner .background {
    transform: scale(1);
    transition: 6.5s transform;
  }

  /* Other stuff */
  .banner h1 {
    color: #EEE;
    margin: 0;
    line-height: 20rem;
    font-size: 4rem;
    text-transform: uppercase;
    text-shadow: 0 0 .3rem black;
    font-weight: 600;
  }

  .rotate-4 {
    transform: rotate(-4deg);
  }

  .-rotate-4 {
    transform: rotate(4deg);
  }
</style>

<script type="text/javascript" src="<?= base_url('assets/js/TweenMax.2.1.3.min.js') ?>"></script>
<script>
  window.onload = function() {
    document.body.className += ' loaded'
  };

</script>

<?= $this->endSection() ?>
