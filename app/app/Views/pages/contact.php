<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>Guides et scoutes de Gosselies - Contact
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="relative bg-white">
    <div class="absolute inset-0">
        <div class="absolute inset-y-0 left-0 w-1/2 bg-gray-50"></div>
    </div>
    <div class="relative mx-auto max-w-7xl lg:grid lg:grid-cols-5">
        <div class="bg-gray-50 px-6 py-16 lg:col-span-2 lg:px-8 lg:py-24 xl:pr-12 ">
            <div class="mx-auto max-w-lg">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Contactez nous</h2>
                <p class="mt-3 text-lg leading-6 text-gray-500">Nous disposons d'un local situé dans le centre de
                    gosselies.</p>
                <dl class="mt-8 text-base text-gray-500">
                    <div>
                        <dt class="sr-only">Adresse postale</dt>
                        <dd>
                            <p>Rue Henri Belyn 77</p>
                            <p>6041 Gosselies</p>
                        </dd>
                    </div>

                    <div class="mt-6">
                        <dt class="sr-only">Email</dt>
                        <dd class="flex">
                            <?= component('icon', ['name' => 'envelope', 'class' => 'h-6 w-6 flex-shrink-0 text-gray-400']) ?>
                            <a href="mailto:<?= esc($contactEmail, 'attr') ?>" class="ml-3 hover:text-gray-700"><?= esc($contactEmail) ?></a>
                        </dd>
                    </div>
                </dl>
                <div style="width: 100%" class="mt-4"><iframe width="100%" height="200" frameborder="0" scrolling="no"
                        marginheight="0" marginwidth="0"
                        src="https://maps.google.com/maps?width=100%25&amp;height=200&amp;hl=en&amp;q=Rue%20Henri%20Belyn%2077,%206041%20Charleroi+(USGG)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a
                            href="https://www.maps.ie/population/">Calculate population in area</a></iframe></div>

            </div>
        </div>
        <div class="bg-white px-6 py-16 lg:col-span-3 lg:px-8 lg:py-24 xl:pl-12">
            <div class="mx-auto max-w-lg lg:max-w-none">
                <div id="formulaire" class="scroll-mt-8"><?= component('flash') ?></div>
                <form action="<?= base_url('contact') ?>" method="POST" class="grid grid-cols-1 gap-y-6" novalidate x-data="{ sending: false }" @submit="sending = true">
                    <?= \App\Helpers\FormGuard::field() ?>
                    <?= component('field', ['label' => 'Nom - Prénom', 'name' => 'fullname', 'required' => true, 'placeholder' => 'John Doe', 'value' => old('fullname', null, false), 'attrs' => ['autocomplete' => 'name', 'maxlength' => 150]]) ?>
                    <?= component('field', ['label' => 'Adresse e-mail', 'name' => 'email', 'type' => 'email', 'required' => true, 'placeholder' => 'exemple@gmail.com', 'value' => old('email', null, false), 'attrs' => ['autocomplete' => 'email', 'maxlength' => 255]]) ?>
                    <?= component('field', ['label' => 'Téléphone', 'name' => 'phone', 'type' => 'tel', 'placeholder' => '0497/12.34.45', 'value' => old('phone', null, false), 'attrs' => ['autocomplete' => 'tel', 'maxlength' => 30]]) ?>
                    <?= component('field', ['label' => 'Message', 'name' => 'message', 'type' => 'textarea', 'rows' => 5, 'required' => true, 'placeholder' => 'Votre message', 'value' => old('message', null, false), 'attrs' => ['maxlength' => 5000]]) ?>
                    <p class="text-sm text-gray-500">Vos coordonnées servent uniquement à vous répondre.</p>
                    <div>
                        <?= component('button', ['label' => 'Envoyer', 'type' => 'submit', 'size' => 'lg', 'attrs' => [':disabled' => 'sending']]) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- https://www.maps.ie/create-google-map/ -->

<?= $this->endSection() ?>