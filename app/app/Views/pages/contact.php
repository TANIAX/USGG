<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>Contact
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
                            <svg class="h-6 w-6 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <span class="ml-3">contact@gsgosselies.be</span>
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
                <form action="#" method="POST" class="grid grid-cols-1 gap-y-6">
                    <div>
                        <label class="block text-gray-700">Nom - Prénom</label>
                        <input type="text" name="fullname" placeholder="John Doe"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            autofocus="" autocomplete="" required="">
                    </div>
                    <div>
                        <label class="block text-gray-700">Email</label>
                        <input type="email" name="email" placeholder="exemple@gmail.com"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            autofocus="" autocomplete="" required="">
                    </div>
                    <div>
                        <label class="block text-gray-700">Téléphone</label>
                        <input type="text" name="fullname" placeholder="0497/12.34.45"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            autofocus="" autocomplete="" required="">
                    </div>
                    <div>
                        <label class="block text-gray-700">Message</label>
                        <textarea id="message" name="message" placeholder="Votre message" rows="4"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <div>
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- https://www.maps.ie/create-google-map/ -->

<?= $this->endSection() ?>