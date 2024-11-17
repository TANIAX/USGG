<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Guides - Présentation des sections
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl" x-data="app()">
   <!-- Présentation -->
   <div id="presentation" class="overflow-hidden bg-white px-6 py-8 lg:px-8 xl:py-36">
      <div class="mx-auto max-w-max lg:max-w-7xl">
         <div class="relative z-10 mb-8 md:mb-2 md:px-6">
            <div class="max-w-prose lg:max-w-none">
               <p class="mt-2 text-3xl/8 font-bold tracking-tight text-gray-900 sm:text-4xl">
                Présentation des sections
               </p>
            </div>
         </div>
         <div class="relative">
            <svg class="absolute right-0 top-0 -mr-20 -mt-20 hidden md:block md:[overflow-anchor:none]" width="404"
               height="384" fill="none" viewBox="0 0 404 384" aria-hidden="true">
               <defs>
                  <pattern id="95e8f2de-6d30-4b7e-8159-f791729db21b" x="0" y="0" width="20" height="20"
                     patternUnits="userSpaceOnUse">
                     <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                  </pattern>
               </defs>
               <rect width="404" height="384" fill="url(#95e8f2de-6d30-4b7e-8159-f791729db21b)" />
            </svg>
            <svg class="absolute bottom-0 left-0 -mb-20 -ml-20 hidden md:block md:[overflow-anchor:none]"
               width="404" height="384" fill="none" viewBox="0 0 404 384" aria-hidden="true">
               <defs>
                  <pattern id="7a00fe67-0343-4a3c-8e81-c145097a3ce0" x="0" y="0" width="20" height="20"
                     patternUnits="userSpaceOnUse">
                     <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                  </pattern>
               </defs>
               <rect width="404" height="384" fill="url(#7a00fe67-0343-4a3c-8e81-c145097a3ce0)" />
            </svg>
            <div class="relative md:bg-white md:p-6">
               <div class="lg:grid lg:grid-cols-2 lg:gap-6">
                  <div class="prose prose-lg prose-indigo text-gray-500 lg:max-w-none">
                     <p>Les Guides sont un mouvement de jeunesse reconnu par la Fédération
                        Wallonie-Bruxelles. Le mouvement
                        est ouvert à tous à partir de 5 ans.
                        La mission est de soutenir les jeunes dans leur développement global et ce par le jeu,
                        l’amusement, le plaisir partagé mais aussi par la découverte et la transmission de
                        valeurs.
                     </p>
                     <p class="mt-4">
                        Les principales défendues sont le partage, le respect, la responsabilité, l’ouverture et
                        la confiance.
                        Mais on y retrouve aussi l’amitié, la simplicité, l’honnêteté, la loyauté, la
                        créativité, la coopération, le volontariat, etc.
                     </p>
                  </div>
                  <div class="prose prose-lg prose-indigo mt-6 text-gray-500 lg:mt-0">
                     <p>
                        Ces valeurs sont les guidelines de l’animation hebdomadaire et au fil de l’année.
                        L’animation se fait par des jeunes bénévoles qui ont à cœur d’accompagner les jeunes de
                        la manière la plus ajustée possible.
                     </p>
                     <p class="mt-4">
                        Toute la pédagogie dont le projet pédagogique, les fondements du mouvement mais aussi
                        son histoire sont à lire sur le site : <a class="link" href="https://www.guides.be/">www.guides.be</a>
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

    <!-- Nutons -->
    <div id="nutons" class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                <!-- Desktop Image Container -->
                <div class="hidden md:block md:mt-14 lg:pr-4">
                    <div class="relative overflow-hidden rounded-3xl px-6 pb-9 pt-64 shadow-2xl sm:px-12 lg:max-w-lg lg:px-8 lg:pb-8 xl:px-10 xl:pb-10">
                    <img class="absolute inset-0 h-full w-full" src="<?= base_url('assets/img/logo-nutons.png') ?>" alt="logo-nuton">
                    </div>
                </div>
                <!-- Text and Mobile Image Container -->
                <div>
                    <div class="text-base/7 text-gray-700 lg:max-w-lg">
                    <h1 class="mx-auto mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                        Nutons ami de tous
                    </h1>
                    <!-- Mobile Image -->
                    <div class="relative overflow-hidden rounded-3xl my-6 pt-32 shadow-2xl w-2/3 mx-auto lg:hidden">
                        <img class="absolute inset-0 h-full w-full" src="<?= base_url('assets/img/logo-nutons.png') ?>" alt="logo-nuton">
                    </div>
                    <!-- Description -->
                    <div class="max-w-xl">
                        <p class="mt-6">L’aventure des nutons commence à partir de 5 ans. La Chaumière a pour
                            objectif de rencontrer et s’ouvrir aux autres, de reconnaître et d’exprimer ses
                            émotions, de favoriser la découverte et l’épanouissement mais aussi de faire ses
                            premiers pas vers l’autonomie.
                        </p>
                        <p class="mt-8">Pour se faire, les chefs prennent soin des animés, de leur sécurité, de leur
                            bien-être.
                        </p>
                        <p class="mt-6">Chaque semaine, ils sont invités à découvrir de nouveaux univers pour
                            partager ensemble de beaux moments d’amusement, pour apprendre à se connaître mais aussi
                            à connaître les autres.
                        </p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <!-- Lutins -->
   <div id="lutins" class="bg-white py-16 reveal">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
         <div class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            <!-- Text and Mobile Image Container -->
            <div>
               <div class="text-base/7 text-gray-700 lg:max-w-lg">
                  <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                     Lutins de notre mieux
                  </h1>
                    <!-- Mobile Image -->
                    <div class="relative overflow-hidden rounded-3xl my-6 pt-32 shadow-2xl w-2/3 mx-auto lg:hidden">
                        <img class="absolute inset-0 h-full w-full" src="<?= base_url('assets/img/logo-lutins.png') ?>" alt="logo-lutins">
                    </div>
                  <div class="max-w-xl">
                     <p class="mt-6">Le passage de la Chaumière à la Ronde se fait à 7 ans. Les objectifs des
                        Nutons se poursuivent aux Lutins et d’autres viennent s’y ajouter. Aux Lutins, les
                        jeunes découvrent la vie en plus petits groupes, en sizaines.
                     </p>
                     <p class="mt-8">Elles sont invitées à prendre davantage de responsabilité au fil de l’année
                        et à s’autonomiser de manière ajustée. Le grand passage des Lutins est l’engagement de
                        la promesse. Pour se faire, les jeunes sont accompagnées durant l’année et le camp.
                     </p>
                     <p class="mt-6">La promesse Lutin se base sur les Règles d’Or, c’est une manière de
                        s’engager dans la Ronde, de s’impliquer dans la vie des Guides. En retour, la Ronde te
                        fait confiance et t’accueille tel que tu es. Le jeu, le plaisir, les animations, les
                        rencontres restent au centre du mouvement.
                     </p>
                  </div>
               </div>
            </div>
               <!-- Desktop Image Container -->
            <div class="hidden md:block md:mt-24 lg:pr-4">
               <div class="relative overflow-hidden rounded-3xl px-6 pb-9 pt-64 shadow-2xl sm:px-12 lg:max-w-lg lg:px-8 lg:pb-8 xl:px-10 xl:pb-10">
                  <img class="absolute inset-0 h-full w-full" src="<?= base_url('assets/img/logo-lutins.png') ?>"
                     alt="logo=lutins">
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Aventures -->
   <div id="aventures" class="bg-white py-16 reveal">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
         <div class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            <!-- Desktop Image Container -->
            <div class="hidden md:block md:mt-24 lg:pr-4">
               <div
                  class="relative overflow-hidden rounded-3xl px-6 pb-9 pt-64 shadow-2xl sm:px-12 lg:max-w-lg lg:px-8 lg:pb-8 xl:px-10 xl:pb-10">
                  <img class="absolute inset-0 h-full w-full"
                     src="<?= base_url('assets/img/logo-aventures.png') ?>" alt="logo=aventures">
               </div>
            </div>
            <div>
              <!-- Text and Mobile Image Container -->
               <div class="text-base/7 text-gray-700 lg:max-w-lg">
                  <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                     Aventures toujours prêt
                  </h1>
                  <div class="relative overflow-hidden rounded-3xl my-6 pt-32 shadow-2xl w-2/3 mx-auto lg:hidden">
                        <img class="absolute inset-0 h-full w-full" src="<?= base_url('assets/img/logo-aventures.png') ?>" alt="logo-aventure">
                    </div>
                  <div class="max-w-xl">
                     <p class="mt-6">Bienvenue chez les aventures à partir de 11ans. Le groupe s’appelle la
                        Compagnie. Les valeurs et les expériences continuent de s’ajouter. Maintenant, le groupe
                        est divisé en Patrouille. Au sein de la Patrouille, c’est un véritable petit système qui
                        s’organise et qui s’ajuste entre responsabilité, transmettre et apprendre des
                        compétences techniques (badges), mener des projets, s’investir dans des « bonnes actions
                        », etc. En Patrouille et dans la Compagnie, la guide continue d’apprendre à se découvrir
                        mais aussi à vivre dans la nature, en plein air en respectant son environnement.
                     </p>
                     <p class="mt-8">Lors des années dans la Compagnie, la Guide est invitée à découvrir la Loi
                        Guide et à s’engager dans une deuxième promesse.
                     </p>
                     <p class="mt-6">Tous ces changements et étapes sont accompagnés les chefs. Le plaisir reste
                        au centre. Et le respect du rythme de chacune est fondamental.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Horizons -->
   <div id="horizons" class="bg-white py-16 reveal">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
         <div
            class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none lg:grid-cols-2">
             <!-- Text and Mobile Image Container -->
            <div>
               <div class="text-base/7 text-gray-700 lg:max-w-lg">
                  <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                     Horizons entreprendre
                  </h1>
                  <div class="relative overflow-hidden rounded-3xl my-6 pt-32 shadow-2xl w-2/3 mx-auto lg:hidden">
                        <img class="absolute inset-0 h-full w-full" src="<?= base_url('assets/img/logo-horizons.png') ?>" alt="logo-horizons">
                    </div>
                  <div class="max-w-xl">
                     <p class="mt-6">A 15 ans, les Horizons prennent le relais. Les jeunes intègrent la Chaine.
                        C’est un espace idéal pour apprendre, développer ses compétences, mettre tes compétences
                        au service des autres et assumer ses responsabilités. Chez les Horizons, les jeunes
                        co-construisent un projet commun. L’implication et la présence de chacune est donc
                        essentiel. C’est ensemble que le projet peur aboutir. <br>Le rôle des chefs évolue, ils
                        guident et soutiennent les animés. Les véritables actrices sont les jeunes. Elles sont
                        leur propre moteur
                     </p>
                     <p class="mt-8">Leur année est rythmée de différents projets avec plusieurs objectifs :
                        s’impliquer dans des Entreprises sociales, culturelles, d’animation. Etc. L’objectif
                        final étant d’arriver au camp. 
                     </p>
                     <p class="mt-6">Durant les années dans la Chaine, la formation à l’animation prend plus de
                        place. Effectivement, c’est la dernière section avant de devenir chef. Une attention
                        particulière et l’implication des Horizons dans les sections commencent afin de les
                        préparer à la magnifique aventure d’être chef et d’accompagner des dizaines de jeunes.
                     </p>
                  </div>
               </div>
            </div>
           <!-- Desktop Image Container -->

            <div class="hidden md:block md:mt-40 lg:pr-4">
               <div
                  class="relative overflow-hidden rounded-3xl px-6 pb-9 pt-64 shadow-2xl sm:px-12 lg:max-w-lg lg:px-8 lg:pb-8 xl:px-10 xl:pb-10">
                  <img class="absolute inset-0 h-full w-full"
                     src="<?= base_url('assets/img/logo-horizons.png') ?>" alt="logo=horizons">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   function app() {
       return {
   
       };
   }
</script>

<?= $this->endSection() ?>