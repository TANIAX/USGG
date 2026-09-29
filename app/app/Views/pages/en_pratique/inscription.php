<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
  Guides et scoutes de Gosselies - Inscription
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section>
  <div class="bg-white py-8">
    <div class="container mx-auto flex flex-col items-start xl:flex-row my-12 xl:my-24">
      <!-- LEFT -->
      <div id="demande" class="flex flex-col w-full xl:w-1/3 mt-2 xl:mt-12 px-8 md:px-0 scroll-mt-8">
        <h1 class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">Inscription</h1>
        <p class="text-sm xl:text-base text-gray-500 mb-4">
          Les étapes de l'inscription sont détaillées ci-contre. Remplissez ce formulaire pour introduire une demande :
          vous recevrez une confirmation par e-mail et nous vous recontacterons.
        </p>
        <?= component('flash', ['class' => 'mt-2']) ?>
        <?php $old = fn(string $field) => old($field, null, false); ?>
        <form class="mt-6 space-y-4" action="<?= base_url('en-pratique/inscription') ?>" method="post" novalidate x-data="{ sending: false }" @submit="sending = true">
          <?= \App\Helpers\FormGuard::field() ?>

          <h2 class="text-xl uppercase font-semibold text-gray-700">L'enfant</h2>
          <hr>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <?= component('field', ['label' => 'Prénom', 'name' => 'firstname', 'required' => true, 'value' => $old('firstname'), 'attrs' => ['autocomplete' => 'off', 'maxlength' => 100]]) ?>
            <?= component('field', ['label' => 'Nom', 'name' => 'name', 'required' => true, 'value' => $old('name'), 'attrs' => ['autocomplete' => 'off', 'maxlength' => 100]]) ?>
          </div>
          <?= component('field', ['label' => 'Date de naissance', 'name' => 'birthdate', 'type' => 'date', 'required' => true, 'value' => $old('birthdate')]) ?>
          <?= component('field', ['label' => 'Totem et quali (si applicable)', 'name' => 'totem', 'value' => $old('totem'), 'attrs' => ['maxlength' => 100]]) ?>
          <?= component('field', ['label' => 'Section souhaitée', 'name' => 'section_id', 'type' => 'select', 'options' => $sections, 'value' => $old('section_id'),
            'placeholder' => 'Pas de préférence (selon l\'âge)']) ?>

          <fieldset>
            <legend class="block text-sm font-medium leading-6 text-gray-900">Lien avec l'unité <span class="text-red-600">*</span></legend>
            <div class="mt-2 space-y-2">
              <?php foreach ($relations as $code => [$label, $phase]): ?>
                <label class="flex items-start gap-x-3 text-sm text-gray-700">
                  <input type="radio" name="relation" value="<?= $code ?>" <?= ($old('relation') ?? '') === $code ? 'checked' : '' ?> required class="mt-0.5 h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                  <span><?= esc($label) ?> <span class="text-gray-500">(phase <?= $phase ?>)</span></span>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <h2 class="!mt-8 text-xl uppercase font-semibold text-gray-700">Adresse</h2>
          <hr>
          <div class="grid grid-cols-3 gap-4">
            <?= component('field', ['label' => 'Rue', 'name' => 'street', 'required' => true, 'value' => $old('street'), 'class' => 'col-span-2', 'attrs' => ['autocomplete' => 'address-line1', 'maxlength' => 150]]) ?>
            <?= component('field', ['label' => 'Numéro', 'name' => 'number', 'required' => true, 'value' => $old('number'), 'attrs' => ['maxlength' => 20]]) ?>
            <?= component('field', ['label' => 'Code postal', 'name' => 'zip_code', 'required' => true, 'value' => $old('zip_code'), 'attrs' => ['autocomplete' => 'postal-code', 'inputmode' => 'numeric', 'maxlength' => 5]]) ?>
            <?= component('field', ['label' => 'Localité', 'name' => 'city', 'required' => true, 'value' => $old('city'), 'class' => 'col-span-2', 'attrs' => ['autocomplete' => 'address-level2', 'maxlength' => 100]]) ?>
          </div>

          <h2 class="!mt-8 text-xl uppercase font-semibold text-gray-700">Parent ou responsable</h2>
          <hr>
          <?= component('field', ['label' => 'Nom et prénom', 'name' => 'parent_name', 'required' => true, 'value' => $old('parent_name'), 'attrs' => ['autocomplete' => 'name', 'maxlength' => 150]]) ?>
          <?= component('field', ['label' => 'Adresse e-mail', 'name' => 'parent_email', 'type' => 'email', 'required' => true, 'value' => $old('parent_email'), 'attrs' => ['autocomplete' => 'email', 'maxlength' => 255]]) ?>
          <?= component('field', ['label' => 'Téléphone', 'name' => 'parent_phone', 'type' => 'tel', 'required' => true, 'value' => $old('parent_phone'), 'attrs' => ['autocomplete' => 'tel', 'maxlength' => 30]]) ?>
          <?= component('field', ['label' => 'Remarque', 'name' => 'remark', 'type' => 'textarea', 'rows' => 3, 'value' => $old('remark'),
            'help' => 'Facultatif : informations utiles (besoins particuliers, disponibilités...).', 'attrs' => ['maxlength' => 2000]]) ?>

          <?= component('checkbox', ['name' => 'consent', 'checked' => (bool) $old('consent'), 'class' => 'flex items-start',
            'label' => 'J\'accepte que ces informations soient utilisées par l\'unité pour traiter la demande d\'inscription.', 'attrs' => ['required' => true]]) ?>
          <p class="-mt-4 pl-7 text-sm text-gray-500">Voir notre <a href="<?= base_url('confidentialite') ?>" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">politique de confidentialité</a> et la <a href="<?= base_url('charte') ?>" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">charte de fonctionnement</a> de l'unité.</p>

          <div class="flex items-center justify-end border-t border-gray-900/10 py-4">
            <?= component('button', ['label' => 'Envoyer la demande', 'type' => 'submit', 'size' => 'lg', 'attrs' => [':disabled' => 'sending']]) ?>
          </div>
        </form>
      </div>
      <!-- RIGTH -->
      <div class="ml-0 px-4 xl:ml-12 xl:w-2/3 sticky">
        <div class="border-l-2 mt-10">
          <?php component_open('step', ['number' => '1', 'title' => 'Demande d\'inscription - phase 1', 'index' => 0, 'period' => '1er au 15 juillet']) ?>
              <h3 class="text-xl font-bold text-gray-800">Cette phase concerne :</h3>
              <ul class="list-disc px-8 mt-4">
              <li>Les familles qui ont déjà un enfant inscrit dans l'une des deux unités et qui souhaitent inscrire
              <span class="text-gray-700 font-semibold">un/des frère(s) et soeur(s)</span></li>
              <li><span class="text-gray-700 font-semibold">Les enfants de nos anciens membres actifs</span></li>
              </ul>
          <?= component_close() ?>

          <?php component_open('step', ['number' => '2', 'title' => 'Demande d\'inscription - phase 2', 'index' => 1, 'period' => '15 juillet au 15 septembre']) ?>
              <h3 class="text-xl font-bold text-gray-800">Cette phase concerne :</h3>
              <p class="mt-4">
              <span class="text-gray-700 font-semibold">Toutes les familles sans distinction.</span> Elle est ouverte
              à partir du 1er juillet, et de la clôture de la phase 1 d'inscription, jusqu'à l'ouverture des
              inscriptions pour l'année suivante.
              </p>
          <?= component_close() ?>

          <?php component_open('step', ['number' => '3', 'title' => 'Gestion des inscriptions', 'index' => 2]) ?>
              <p>
              Afin de garantir une animation de qualité et cohérente avec les méthodes des Guides & Scouts (où la
              relation est au centre du dispositif pédagogique), nous limitons volontairement la taille de nos
              sections.
              </p>
              <p class="mt-2">
              Il ne nous sera peut-être <span class="text-gray-700 font-semibold">pas possible de répondre
              positivement à toutes les demandes d'inscription</span>.
              </p>
              <ul class="list-disc px-8 mt-4">
              <li>Les familles qui ont soumis un formulaire de phase 1 seront contactées en premier lieu, ensuite
              celles qui ont soumis un formulaire de phase </li>
              <li>Les demandes seront traitées par ordre de réception pour chaque phase</li>
              <li><span class="text-gray-700 font-semibold">Les enfants que nous ne pourront accueillir directement
              seront placés en liste d'attente (étape 4A)</span></li>
              <li><span class="text-gray-700 font-semibold">Les enfants qui peuvent être accueillis seront invités à
              un essai (4B)</span></li>
              </ul>
          <?= component_close() ?>

          <?php component_open('step', ['number' => '4A', 'title' => 'Liste d\'attente', 'index' => 3]) ?>
              <p>
              Si nous ne pouvons répondre favorablement à votre demande d'inscription, nous vous placerons sur liste
              d'attente et vous recontacterons dès qu'il sera possible d'accueillir votre enfant. 
              </p>
              <p class="text-gray-700 font-semibold mt-2">Si aucune place ne se libère durant l'année, vous serez averti de
              l'ouverture de la campagne d'inscription pour l'année suivante (étape 6).
              </p>
              <p class="mt-2">
              Afin de garantir une gestion saine de vos données personnelles, la liste d'attente sera effacée dès
              l'ouverture de la prochaine campagne d'inscription.
              </p>
          <?= component_close() ?>

          <?php component_open('step', ['number' => '4B', 'title' => 'Pré-inscription (réunion d\'essai)', 'index' => 4, 'period' => 'Sur invitation']) ?>
              <p>
              Si nous répondons favorablement à la demande d'inscription, nous vous proposerons <span
              class="text-gray-700 font-semibold">une réunion d'essai avant l'inscription officielle.</span>
              L'objectif est double :
              </p>
              <ul class="list-disc px-8 mt-2">
              <li>Rencontrer l'équipe d'animation qui prendra en charge votre enfant</li>
              <li>Nous assurer que votre enfant accroche bien à l'animation proposée</li>
              </ul>
          <?= component_close() ?>

          <?php component_open('step', ['number' => '5', 'title' => 'Inscription officielle', 'index' => 5, 'period' => 'Après essai']) ?>
              <p>
              Après la réunion d'essai, vous pourrez confirmer l'inscription en payant la <a
              href="/en-pratique/cotisation"
              class="font-medium text-blue-600 hover:underline">cotisation annuelle</a> et en
              remettant la <span class="text-gray-700 font-semibold">fiche médicale</span> au staff d'animation.
              </p>
              <p class="mt-2">
              En inscrivant votre enfant vous acceptez notre <a href="#"
              class="font-medium text-blue-600 hover:underline">Charte de fonctionnement</a>.
              </p>
          <?= component_close() ?>

          <?php component_open('step', ['number' => '6', 'title' => 'Ouverture de la nouvelle campagne d\'inscription', 'index' => 6]) ?>
              <p>
              A l'ouverture des inscriptions pour l'année scolaire suivante, la liste d'attente sera remise à zéro et
              les familles qui y sont toujours inscrites seront recontactées.
              </p>
          <?= component_close() ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>