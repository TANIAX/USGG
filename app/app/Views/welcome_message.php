<?= $this->extend('default') ?>
<?= $this->section('page_title') ?>Acceuil<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Banner -->
<header class="banner hidden md:block">
  <span class="background"></span>
  <div class="animate__animated animate__slideInUp animate__slow">
    <h1>UNITÉ GUIDE ET SCOUT DE GOSSELIES</h1>
  </div>
</header>


<!-- News -->
<div class="bg-white py-8 sm:py-12">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Actualités</h2>
      <p class="mt-2 text-lg leading-8 text-gray-600">En ce moment chez les scouts et guides de <span class="font-bold">Gosselies</span>.</p>
    </div>
    <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">

    <?php if (!empty($news) && is_array($news)) : ?>
    <?php foreach ($news as $key=> $item) : ?>
      <article class="flex flex-col items-start justify-between animate__animated <?= getAnimateClass($key) ?> animate__slow animate__delay-1s">
        <div class="relative w-full">
          <img src="<?= $item->picture ?>">
          <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
        <div class="max-w-xl">
          <div class="mt-8 flex items-center gap-x-4 text-xs">
            <p class="text-gray-500"><?= $item->created_at->format('d/m/Y') ?></time>
            <a href="#" class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100"><?= $item->category->name ?></a>
          </div>
          <div class="group relative">
            <h3 class="mt-3 text-lg text-ellipsis whitespace-nowrap overflow-hidden font-semibold leading-6 text-gray-900 group-hover:text-gray-600" style="max-width: 350px;">
              <a href="#">
                <span class="absolute inset-0"></span>
                <?= $item->title ?>
              </a>
            </h3>
            <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600"><?= $item->content ?></p>
          </div>
          <div class="relative mt-8 flex items-center gap-x-4">
            <img src="<?= $item->author->picture ?>" alt="" class="h-10 w-10 rounded-full bg-gray-100">
            <div class="text-sm leading-6">
              <p class="font-semibold text-gray-900">
                <a href="#">
                  <span class="absolute inset-0"></span>
                  <?= $item->author->totem ?>
                </a>
              </p>
              <p class="text-sm font-semibold leading-6 text-indigo-600"><?= $item->author->user_type->name ?></p>
            </div>
          </div>
        </div>
      </article>
      <?php endforeach ?>
    <?php else : ?>
      <p>Aucune actualité pour le moment</p>
    <?php endif ?>

    </div>
  </div>
  <!-- More Post -->
  <div id="more_post" class="relative flex flex-col items-center justify-center overflow-hidden py-12 sm:py-24 bg-white">
    <button id="more_post_button" type="button" class="rounded-md px-6 py-2.5 tracking-widest uppercase text-sm font-semibold text-indigo-600 shadow-sm hover:bg-indigo-50 reveal">voir plus d'actualités</button>
  </div>
</div>



<!-- Team -->
<div class="bg-slate-100 py-24 sm:py-32">
  <div class="mx-auto max-w-7xl">
    <div id="team_header" class="mx-auto px-6 lg:px-8 animate__animated animate__slow">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Portrait des chefs</h2>
      <!-- <p class="mt-6 text-lg leading-8 text-gray-600">Les unités possèdes chacuns leurs propres chefs.</p> -->
    </div>
    <ul id="team_list" role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-2 gap-x-8 gap-y-16 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6 reveal">
      <?php if (!empty($users) && is_array($users)) : ?>
        <?php foreach ($users as $user) : ?>
          <li>
            <img class="mx-auto h-24 w-24 rounded-full" src="<?= $user->picture ?>" alt="">
            <h3 class="mt-6 text-base font-semibold leading-7 tracking-tight text-gray-900"><?= $user->totem ?></h3>
            <p class="text-sm font-semibold leading-6 text-indigo-600"><?= $user->user_type ?></p>
          </li>
        <?php endforeach ?>
      <?php else : ?>
        <li>
          <p class="text-sm font-semibold leading-6 text-indigo-600">Aucun chef pour le moment</p>
        </li>
      <?php endif ?>
    </ul>
  </div>
</div>

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
    height: 400px;
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
</style>

<script>
  window.onload = function() {
    document.body.className += ' loaded'
  };
</script>

<?= $this->endSection() ?>


<?php
function getAnimateClass($index){
  if($index % 2 == 0)
    return "animate__bounceInLeft";
  else
    return "animate__bounceInRight";
}

