<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
  Guides et scoutes de Gosselies - Inscription
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section>
  <div class="bg-white py-8">
    <div class="container mx-auto flex flex-col items-start xl:flex-row my-12 xl:my-24">
      <!-- LEFT -->
      <div class="flex flex-col w-full sticky xl:top-36 xl:w-1/3 mt-2 xl:mt-12 px-8 md:px-0">
        <p class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">Inscription</p>
        <p class="text-sm xl:text-base text-gray-500 mb-4">
          Vous pouvez retrouver ici les informations concernant le déroulement des phases d'inscription.
        </p>
        <form class="mt-6" action="/auth/login" method="post">
          <h2 class="text-xl uppercase font-semibold text-gray-700">Informations du membre</h2>
          <hr class="mb-4">

          <div>
            <label class="block text-gray-700">Nom</label>
            <input type="text" name="name" id="name" title="nom" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <div class="mt-4">
            <label class="block text-gray-700">Prénom</label>
            <input type="text" name="firstname" id="firstname" title="prénom" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <div class="mt-4">
            <label class="block text-gray-700">Totem et quali (si applicable)</label>
            <input type="text" name="tomtem" id="totem" title="totem & quali" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="">
          </div>

          <div class="mt-4">
            <label class="block text-gray-700">Date de naissance</label>
            <input type="text" name="date" id="date" title="date de naissance" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <h2 class="text-xl uppercase font-semibold text-gray-700 mt-6">Adresse</h2>
          <hr class="mb-4">

          <div>
            <label class="block text-gray-700">Rue</label>
            <input type="text" name="street" id="street" title="rue" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <div class="mt-4">
            <label class="block text-gray-700">Numéro</label>
            <input type="text" name="number" id="number" title="numéro" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <div class="mt-4">
            <label class="block text-gray-700">Code postal</label>
            <input type="text" name="zipCode" id="zip-code" title="code postal" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <div class="mt-4">
            <label class="block text-gray-700">Localité</label>
            <input type="text" name="city" id="city" title="localité" placeholder=""
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              autofocus="" autocomplete="" required>
          </div>

          <div class="flex items-center justify-end border-t border-gray-900/10 py-4">
            <button type="submit"
              class="rounded-md bg-indigo-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Faire
              une demande</button>
          </div>
        </form>
      </div>
      <!-- RIGTH -->
      <div class="ml-0 px-4 xl:ml-12 xl:w-2/3 sticky">
        <div class="border-l-2 mt-10">
          <!-- Card 1 -->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-between lg:items-center w-full px-4">
              <h2 class="text-gray-800">Demande d'inscription - phase 1</h2>
              <label class="text-base text-gray-500">1er au 15 juillet</label>
            </section>
            <!-- Dot Follwing the Left Vertical Line -->
            <div
              class="w-7 h-7 bg-slate-100 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[85px] md:mt-0">
              1
            </div>
            <!-- Line that connecting the box with the vertical line -->
            <div class="w-10 h-1 bg-slate-50 absolute -left-10 z-0 top-24"></div>
            <!-- Content that showing in the box -->
            <div class="flex-auto bg-slate-100 p-4 rounded-lg shadow-lg">
              <h3 class="text-xl font-bold text-gray-800">Cette phase concerne :</h3>
              <ul class="list-disc px-8 mt-4">
                <li>Les familles qui ont déjà un enfant inscrit dans l'une des deux unités et qui souhaitent inscrire
                  <span class="text-gray-700 font-semibold">un/des frère(s) et soeur(s)</span></li>
                <li><span class="text-gray-700 font-semibold">Les enfants de nos anciens membres actifs</span></li>
              </ul>
            </div>
          </div>

          <!-- Card 2-->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-between lg:items-center w-full px-4">
              <h2 class="text-gray-800">Demande d'inscription - phase 2</h2>
              <label class="text-base text-gray-500">15 juillet au 15 septembre</label>
            </section>
            <div
              class="w-7 h-7 bg-sky-50 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[85px] md:mt-0">
              2
            </div>
            <div class="w-10 h-1 bg-sky-50 absolute -left-10 z-0 top-24"></div>
            <div class="flex-auto bg-sky-50 p-4 rounded-lg shadow-lg">
              <h3 class="text-xl font-bold text-gray-800">Cette phase concerne :</h3>
              <p class="mt-4">
                <span class="text-gray-700 font-semibold">Toutes les familles sans distinction.</span> Elle est ouverte
                à partir du 1er juillet, et de la clôture de la phase 1 d'inscription, jusqu'à l'ouverture des
                inscriptions pour l'année suivante.
              </p>
            </div>
          </div>

          <!-- Card 3 -->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-start lg:items-center w-full px-4">
              <h2 class="text-gray-800">Gestion des inscriptions</h2>
            </section>
            <div
              class="w-7 h-7 bg-slate-100 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[85px] md:mt-0">
              3
            </div>
            <div class="w-10 h-1 bg-slate-50 absolute -left-10 z-0 top-24"></div>
            <div class="flex-auto bg-slate-100 p-4 rounded-lg shadow-lg">
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
            </div>
          </div>


          <!-- Card 4-->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-start lg:items-center w-full px-4">
              <h2 class="text-gray-800">Liste d'attente</h2>
            </section>
            <div
              class="w-7 h-7 bg-sky-50 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[85px] md:mt-0">
              4A
            </div>
            <div class="w-10 h-1 bg-sky-50 absolute -left-10 z-0 top-24"></div>
            <div class="flex-auto bg-sky-50 p-4 rounded-lg shadow-lg">
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
            </div>
          </div>

          <!-- Card 5 -->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-between lg:items-center w-full px-4">
              <h2 class="text-gray-800">Pré-inscription (réunion d'essai)</h2>
              <label class="text-base text-gray-500">Sur invitation</label>
            </section>
            <div
              class="w-7 h-7 bg-slate-100 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[85px] md:mt-0">
              4B
            </div>
            <div class="w-10 h-1 bg-slate-50 absolute -left-10 z-0 top-24"></div>
            <div class="flex-auto bg-slate-100 p-4 rounded-lg shadow-lg">
              <p>
                Si nous répondons favorablement à la demande d'inscription, nous vous proposerons <span
                  class="text-gray-700 font-semibold">une réunion d'essai avant l'inscription officielle.</span>
                L'objectif est double :
              </p>
              <ul class="list-disc px-8 mt-2">
                <li>Rencontrer l'équipe d'animation qui prendra en charge votre enfant</li>
                <li>Nous assurer que votre enfant accroche bien à l'animation proposée</li>
              </ul>
            </div>
          </div>

          <!-- Card 6 -->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-between lg:items-center w-full px-4">
              <h2 class="text-gray-800">Inscription officielle</h2>
              <label class="text-base text-gray-500">Après essai</label>
            </section>
            <div
              class="w-7 h-7 bg-sky-50 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[85px] md:mt-0">
              5
            </div>
            <div class="w-10 h-1 bg-sky-50 absolute -left-10 z-0 top-24"></div>
            <div class="flex-auto bg-sky-50 p-4 rounded-lg shadow-lg">
              <p>
                Après la réunion d'essai, vous pourrez confirmer l'inscription en payant la <a
                  href="/en-pratique/cotisation"
                  class="font-medium text-blue-600 dark:text-blue-500 hover:underline">cotisation annuelle</a> et en
                remettant la <span class="text-gray-700 font-semibold">fiche médicale</span> au staff d'animation.</a>
              </p>
              <p class="mt-2">
                En inscrivant votre enfant vous acceptez notre <a href="#"
                  class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Charte de fonctionnement</a>.
              </p>
            </div>
          </div>

          <!-- Card 7 -->
          <div
            class="transform transition cursor-pointer hover:-translate-y-2 ml-10 relative flex items-center mb-28 rounded mb-10 flex-col space-y-4 md:space-y-0">
            <section class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-start lg:items-center w-full px-4">
              <h2 class="text-gray-800">Ouverture de la nouvelle campagne d'inscription</h2>
            </section>
            <div
              class="w-7 h-7 bg-slate-100 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold  top-[65px] md:mt-0">
              6
            </div>
            <div class="w-10 h-1 bg-slate-50 absolute -left-10 z-0 top-20"></div>
            <div class="flex-auto bg-slate-100 p-4 rounded-lg shadow-lg">
              <p>
                A l'ouverture des inscriptions pour l'année scolaire suivante, la liste d'attente sera remise à zéro et
                les familles qui y sont toujours inscrites seront recontactées.
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>