<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>Cotisations
<?= $this->endSection() ?>
<?= $this->section('content') ?>


<div class="relative isolate overflow-hidden bg-gradient-to-b from-indigo-100/20 sm:pt-4 md:pt-4" x-data="app()"
  x-cloak>
  <div
    class="absolute inset-y-0 right-1/2 -z-10 -mr-96 w-[200%] origin-top-right skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:-mr-80 lg:-mr-96"
    aria-hidden="true"></div>
  <div class="mx-auto max-w-7xl px-6 py-8 md:py-20 xl:py-20 lg:px-8">
    <div class="mx-auto max-w-2xl xl:mx-0 grid grid-cols-2 gap-4 xl:max-w-none">
      <!-- Introduction -->
      <div class="mt-6 max-w-xl lg:mt-0 col-span-2 xl:col-span-1">
        <h1 class="max-w-2xl text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Cotisations</h1>
        <p class="text-lg leading-8 text-gray-600 py-4 mt-8">
          Comme chaque année, nous vous demandons de payer une cotisation annuelle pour inscrire votre enfant chez les
          Guides et les Scouts de Gosselies. Cette cotisation sert à fournir aux membres une assurance pour les
          accidents corporels éventuels, à financer certains services <span class="text-sm">(formations, supports
            pédagogiques, publications, etc.)</span> et contribue modestement à l'entretien de nos infrastructures.
        </p>
      </div>

      <!-- Récap cotisation -->
      <div class="mt-6 max-w-xl lg:mt-0 col-span-2 xl:col-span-1">
        <h2
          class="max-w-2xl text-xl font-bold tracking-tight text-gray-900 uppercase  py-2 sm:text-4xl lg:col-span-2 xl:col-auto">
          COMBIEN COÛTE LA COTISATION ?
        </h2>
        <p class="text-lg leading-8 text-gray-600 py-4 mt-8">
          Le prix de la cotisation dépend du nombre de personnes inscrites chez les Scouts et les Guides dans un même
          foyer (adresse commune).
        </p>
        <p class="text-lg leading-8 text-gray-600 py-2">
          <span class="font-medium">Une réduction de 5€</span> est applicable pour les personnes qui disposent d'un
          <span class="font-medium">brevet d'animateur</span> de centre de vacances ou l'équivalent.
        </p>
      </div>

      <!-- Tableau de cotisation -->
      <div class="mt-6 lg:mt-0 px-4  col-span-2 sm:px-6 lg:px-8">
        <div class="mt-8 flow-root">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
              <table class="min-w-full divide-y divide-gray-300">
                <thead>
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                      Nombre de personne</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Brevet</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Prix</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-gray-500 sm:pl-0" colspan="2">1
                      personne
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500"><span
                        class="font-medium text-gray-900"><?= $pricing->getTier1() ?>€</span> par personne</td>
                  </tr>

                  <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-gray-500 sm:pl-0" colspan="2">2
                      personnes
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500"><span
                        class="font-medium text-gray-900"><?= $pricing->getTier2() ?>€</span> par personne</td>
                  </tr>

                  <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-gray-500 sm:pl-0" colspan="2">3
                      personnes
                      ou plus</td>
                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500"><span
                        class="font-medium text-gray-900"><?= $pricing->getTier3() ?>€</span> par personne</td>
                  </tr>

                  <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-gray-500 sm:pl-0">
                      <div class="relative">
                        <label for="nombre"
                          class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium text-gray-900">Nombre</label>
                        <input type="number" name="nombre" id="nombre" @change="calcul()"
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4"
                          min="0" max="9" placeholder="5">
                      </div>
                    </td>

                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-gray-500 sm:pl-0">
                      <div class="flex items-center">
                        <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                        <button type="button" @click="reductionActive = !reductionActive; calcul()"
                          x-bind:class="reductionActive ? 'bg-indigo-600' : 'bg-gray-200'"
                          class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                          role="switch" aria-checked="false" aria-labelledby="annual-billing-label" id="brevet">
                          <span aria-hidden="true" x-bind:class="reductionActive ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <span class="ml-3 text-sm hidden md:block font-medium text-gray-900" id="foyer">
                          Foyer en possession d'un brevet d'animateur
                        </span>
                      </div>
                    </td>

                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500"><span
                        class="font-medium text-gray-900" id="montant"></span> par personne</td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>


      </div>

      <!-- Modalités de paiements  -->
      <div class="mt-20 lg:mt-32 lg:mt-0 col-span-2">
        <h2 class="max-w-2xl text-xl font-bold tracking-tight text-gray-900 uppercase py-2 sm:text-4xl ">
          MODALITÉS DE PAIEMENT
        </h2>
        <p class="text-lg leading-8 text-gray-600 py-4 mt-8">
          La cotisation est à payer sur le compte <span class="font-medium">BE84 7320 5580 4959</span> avec pour
          communication le <span class="font-medium">nom de famille</span> et le
          (ou les) <span class="font-medium">prénom(s) des personnes</span> (essentiel pour la reconnaissance, le nom du
          propriétaire du compte n'étant
          pas toujours le même que celui des enfants) Le virement doit être effectué pour le <span
            class="font-medium">31 octobre au plus tard.</span>
        </p>
        <p class="text-xl leading-8 text-gray-600 py-2 mt-8 font-medium">Le prix des cotisations ne doit pas être un
          frein pour nous rejoindre.</p>
        <p class="text-lg text-gray-600"><a href="/contact"
            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Contactez-nous</a> si vous avez le
          moindre problème de paiement, nous trouverons une solution ensemble.</p>
      </div>
    </div>

  </div>

  <div class="absolute inset-x-0 bottom-0 -z-10 h-24 bg-gradient-to-t from-white sm:h-32"></div>
</div>
</article>

<script>
  function app() {
    return {
      reductionActive: false,

      calcul() {
        const input = document.getElementById('nombre');
        const montant = document.getElementById('montant');
        const brevet = document.getElementById('brevet');

        const reduction = <?= $pricing->getReduction() ?>;
        const nombre = input.value;

        if (nombre == '' || +nombre <= 0) {
          montant.innerHTML = '';
          input.value = '';
          return;
        }

        if (nombre < 2) {
          const prix = <?= $pricing->getTier1() ?>;
          const total = nombre * prix - (this.reductionActive ? reduction : 0);
          montant.innerHTML = total + '€';
        } else if (nombre < 3) {
          const prix = <?= $pricing->getTier2() ?>;
          const total = nombre * prix - (this.reductionActive ? reduction : 0);
          montant.innerHTML = total + '€';
        } else if (nombre < 10) {
          const prix = <?= $pricing->getTier3() ?>;
          const total = nombre * prix - (this.reductionActive ? reduction : 0);
          montant.innerHTML = total + '€';
        }
        else {
          input.value = 9;
          montant.innerHTML = 0;
          calcul();
        }
      }
    }
  }
</script>

<?= $this->endSection() ?>