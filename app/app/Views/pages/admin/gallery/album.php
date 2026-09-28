<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Album <?= esc($album->title) ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="mt-12 mb-8 border-b py-4 sm:flex sm:items-center">
         <div class="sm:flex-auto">
            <a href="/admin/galerie" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Tous les albums</a>
            <h1 class="mt-2 font-semibold text-4xl leading-tight text-gray-900"><?= esc($album->title) ?></h1>
            <p class="mt-1 text-sm text-gray-500">
               <?= $album->branch === 'GUIDE' ? 'Guides' : 'Scouts' ?>
               · <span x-text="photoCount(photos.length)"></span>
               <span x-show="privateCount > 0" x-text="` dont ${privateCount} réservée${privateCount > 1 ? 's' : ''} aux personnes connectées`"></span>
            </p>
         </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="/galerie/album/<?= $album->id ?>" target="_blank"
               class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
               Voir l'album public
            </a>
         </div>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <!-- Upload -->
      <section class="mb-10">
         <h2 class="text-lg font-semibold text-gray-900">Ajouter des photos</h2>
         <label class="mt-3 inline-flex items-center gap-x-2 text-sm text-gray-700">
            <input type="checkbox" x-model="newPhotosPublic" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            Visibles sans compte
         </label>
         <p class="text-sm text-gray-500" x-show="!newPhotosPublic">Les photos envoyées seront visibles uniquement par les personnes connectées.</p>

         <label class="mt-3 flex cursor-pointer justify-center rounded-lg border-2 border-dashed border-gray-900/25 px-6 py-8 hover:border-indigo-400 hover:bg-gray-50">
            <input type="file" accept="image/*" multiple class="sr-only" @change="addFiles($event.target.files); $event.target.value = ''">
            <span class="text-center">
               <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
               </svg>
               <span class="mt-4 inline-block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm">Choisir des photos</span>
               <span class="mt-2 hidden text-sm text-gray-600 sm:block">ou glissez-les n'importe où sur la page</span>
               <span class="mt-1 block text-xs text-gray-500">Autant de photos que vous voulez. Elles sont réduites avant l'envoi et leurs données de localisation sont supprimées.</span>
            </span>
         </label>

         <!-- Upload summary: one line for the whole batch, the details on demand -->
         <div x-show="uploads.length > 0" class="mt-4 rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-2 text-sm">
               <p class="font-medium text-gray-900" x-text="uploadSummary"></p>
               <div class="flex gap-x-4">
                  <button type="button" x-show="failedUploads.length > 0 && !uploading" @click="retryFailed()"
                     class="font-semibold text-indigo-600 hover:text-indigo-500" x-text="`Réessayer les échecs (${failedUploads.length})`"></button>
                  <button type="button" @click="showUploadDetails = !showUploadDetails" class="font-medium text-gray-500 hover:text-gray-700"
                     x-text="showUploadDetails ? 'Masquer le détail' : 'Voir le détail'"></button>
                  <button type="button" x-show="!uploading" @click="uploads = []; showUploadDetails = false" class="font-medium text-gray-500 hover:text-gray-700">Fermer</button>
               </div>
            </div>
            <div class="mt-3 h-2 overflow-hidden rounded-full bg-gray-200">
               <div class="h-2 rounded-full transition-all" :class="failedUploads.length && !uploading ? 'bg-amber-500' : (uploading ? 'bg-indigo-600' : 'bg-green-500')"
                  :style="`width: ${uploadProgress}%`"></div>
            </div>

            <!-- Failed photos are always listed -->
            <ul x-show="failedUploads.length > 0 && !showUploadDetails" class="mt-3 max-h-40 space-y-1 overflow-y-auto text-sm">
               <template x-for="upload in failedUploads" :key="upload.key">
                  <li class="flex justify-between gap-x-3"><span class="truncate text-gray-700" x-text="upload.name"></span><span class="flex-none text-red-600" x-text="upload.error"></span></li>
               </template>
            </ul>

            <ul x-show="showUploadDetails" class="mt-3 max-h-64 space-y-1 overflow-y-auto text-sm">
               <template x-for="upload in uploads" :key="upload.key">
                  <li class="flex justify-between gap-x-3">
                     <span class="truncate text-gray-700" x-text="upload.name"></span>
                     <span class="flex-none text-xs" :class="upload.error ? 'text-red-600' : 'text-gray-500'"
                        x-text="upload.error || (upload.done ? 'Envoyée' : (upload.started ? upload.progress + ' %' : 'En attente'))"></span>
                  </li>
               </template>
            </ul>
         </div>
      </section>

      <!-- Photos -->
      <section class="mb-10">
         <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Photos <span class="text-gray-400" x-text="`(${photos.length})`"></span></h2>
            <div class="flex gap-x-4 text-sm" x-show="photos.length > 0">
               <button type="button" @click="selectAll()" x-show="selected.length < photos.length" class="font-semibold text-indigo-600 hover:text-indigo-500">Tout sélectionner</button>
               <button type="button" @click="selected = []" x-show="selected.length > 0" class="font-semibold text-gray-600 hover:text-gray-800">Désélectionner</button>
            </div>
         </div>
         <p class="mt-1 text-sm text-gray-500">
            Cochez des photos<span class="hidden sm:inline"> (Maj + clic pour une série)</span> pour les modifier ensemble, ou cliquez sur le cadenas d'une photo pour changer sa visibilité.
         </p>

         <ul role="list" class="mt-4 grid grid-cols-3 gap-2 sm:grid-cols-4 lg:grid-cols-6">
            <template x-for="(photo, index) in photos" :key="photo.id">
               <li class="relative overflow-hidden rounded-md bg-gray-100 ring-1 ring-gray-200">
                  <a :href="`/galerie/photo/${photo.id}`" target="_blank">
                     <img :src="`/galerie/photo/${photo.id}/miniature`" alt="" class="aspect-square w-full object-cover" loading="lazy">
                  </a>
                  <!-- Frame of a selected photo, above the image -->
                  <div x-show="isSelected(photo)" class="pointer-events-none absolute inset-0 rounded-md bg-indigo-600/15 ring-4 ring-inset ring-indigo-600"></div>
                  <label class="absolute left-0 top-0 cursor-pointer p-1.5" :title="isSelected(photo) ? 'Désélectionner' : 'Sélectionner'">
                     <input type="checkbox" :checked="isSelected(photo)" @click="toggleSelection(photo, index, $event)"
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 shadow focus:ring-indigo-600">
                  </label>
                  <div class="flex items-center justify-between gap-x-1 bg-white px-1.5 py-1">
                     <button type="button" @click="toggleVisibility(photo)" :disabled="!!photo.busy"
                        class="inline-flex items-center gap-x-1 rounded px-1 py-0.5 text-xs font-medium"
                        :class="photo.is_public ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
                        :title="photo.is_public ? 'Visible sans compte : cliquer pour la réserver aux personnes connectées' : 'Réservée aux personnes connectées : cliquer pour la rendre visible sans compte'">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                           <path x-show="!photo.is_public" fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                           <path x-show="photo.is_public" fill-rule="evenodd" d="M14.5 1A4.5 4.5 0 0010 5.5V9H3a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-1.5V5.5a3 3 0 116 0v2.75a.75.75 0 001.5 0V5.5A4.5 4.5 0 0014.5 1z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="photo.is_public ? 'Publique' : 'Privée'"></span>
                     </button>
                     <button type="button" @click="deletePhotos([photo])" :disabled="!!photo.busy" class="rounded p-0.5 text-gray-400 hover:text-red-600" title="Supprimer">
                        <span class="sr-only">Supprimer</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                           <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                        </svg>
                     </button>
                  </div>
               </li>
            </template>
         </ul>
         <p x-show="photos.length === 0" class="mt-4 text-sm text-gray-500">Aucune photo pour l'instant.</p>
         <p x-show="actionError" x-text="actionError" class="mt-4 text-sm text-red-600"></p>
      </section>

      <!-- Actions on the selected photos (stays visible while scrolling) -->
      <div x-show="selected.length > 0" x-transition class="fixed inset-x-0 bottom-4 z-40 flex justify-center px-4">
         <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 rounded-lg bg-gray-900 px-4 py-3 text-sm text-white shadow-xl">
            <span class="font-semibold" x-text="`${selected.length} photo${selected.length > 1 ? 's' : ''} sélectionnée${selected.length > 1 ? 's' : ''}`"></span>
            <button type="button" @click="bulk('public')" :disabled="bulkBusy" class="font-medium text-green-300 hover:text-green-200">Visibles sans compte</button>
            <button type="button" @click="bulk('private')" :disabled="bulkBusy" class="font-medium text-amber-300 hover:text-amber-200">Réservées aux membres</button>
            <button type="button" @click="deletePhotos(photos.filter(photo => isSelected(photo)))" :disabled="bulkBusy" class="font-medium text-red-300 hover:text-red-200">Supprimer</button>
            <button type="button" @click="selected = []" class="text-gray-400 hover:text-white">Annuler</button>
         </div>
      </div>

      <!-- Shown while files are dragged over the page: they can be dropped anywhere -->
      <template x-teleport="body">
         <div x-show="dragOver" class="pointer-events-none fixed inset-0 z-[100] flex items-center justify-center bg-indigo-600/80 p-8">
            <div class="rounded-xl border-4 border-dashed border-white px-10 py-12 text-center text-white">
               <p class="text-2xl font-semibold">Déposez les photos</p>
               <p class="mt-1">pour les ajouter à l'album « <?= esc($album->title) ?> »</p>
            </div>
         </div>
      </template>

      <!-- Album information -->
      <section class="mb-10 border-t pt-8" x-data="{ open: <?= session()->getFlashdata('errors') ? 'true' : 'false' ?> }">
         <button type="button" @click="open = !open" class="flex items-center gap-x-2 text-lg font-semibold text-gray-900">
            Informations de l'album
            <svg class="h-5 w-5 text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
               <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
         </button>
         <div x-show="open" class="mt-4 max-w-3xl">
            <form method="POST" action="<?= base_url('admin/galerie/album/' . $album->id . '/update') ?>" class="space-y-6">
               <?= $this->include('pages/admin/gallery/album_fields') ?>
               <div class="flex justify-end">
                  <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Enregistrer</button>
               </div>
            </form>

            <form method="POST" action="<?= base_url('admin/galerie/album/' . $album->id . '/delete') ?>" class="mt-8 rounded-md bg-red-50 p-4"
               @submit="if (!confirm('Supprimer l\'album et toutes ses photos ? Cette action est définitive.')) $event.preventDefault()">
               <p class="text-sm text-red-800">La suppression de l'album efface définitivement toutes ses photos.</p>
               <button type="submit" class="mt-3 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Supprimer l'album</button>
            </form>
         </div>
      </section>
   </div>
</div>

<script>
   // Photos are reduced in the browser before being sent: faster on mobile and below the upload limit of the server
   const MAX_PHOTO_SIZE = 2000;
   const PARALLEL_UPLOADS = 3;

   function app() {
      return {
         uploadUrl: '<?= base_url('admin/galerie/album/' . $album->id . '/upload') ?>',
         bulkUrl: '<?= base_url('admin/galerie/album/' . $album->id . '/photos') ?>',
         photoUrl: '<?= rtrim(base_url('admin/galerie/photo'), '/') ?>',
         maxUploadBytes: <?= (int) $maxUploadKb ?> * 1024,
         photos: <?= $photos ?>,
         newPhotosPublic: true,
         dragOver: false,
         dragDepth: 0,
         uploads: [],
         uploading: false,
         showUploadDetails: false,
         selected: [],
         lastSelectedIndex: null,
         bulkBusy: false,
         actionError: '',

         init() {
            // Files can be dropped anywhere on the page. Without this, a photo dropped next to the zone
            // would be opened by the browser, leaving the page (and stopping the uploads).
            const hasFiles = event => event.dataTransfer && Array.from(event.dataTransfer.types || []).includes('Files');
            window.addEventListener('dragenter', event => { if (hasFiles(event)) { this.dragDepth++; this.dragOver = true; } });
            window.addEventListener('dragleave', event => { if (hasFiles(event) && --this.dragDepth <= 0) { this.dragDepth = 0; this.dragOver = false; } });
            window.addEventListener('dragover', event => { if (hasFiles(event)) event.preventDefault(); });
            window.addEventListener('drop', event => {
               if (!hasFiles(event)) return;
               event.preventDefault();
               this.dragDepth = 0;
               this.dragOver = false;
               this.addFiles(event.dataTransfer.files);
            });

            // Warns before leaving the page while photos are being sent
            window.addEventListener('beforeunload', event => {
               if (this.uploading) {
                  event.preventDefault();
                  event.returnValue = '';
               }
            });
         },

         get privateCount() {
            return this.photos.filter(photo => !photo.is_public).length;
         },

         get failedUploads() {
            return this.uploads.filter(upload => upload.error);
         },

         get uploadProgress() {
            if (!this.uploads.length) return 0;
            return Math.round(this.uploads.reduce((total, upload) => total + upload.progress, 0) / this.uploads.length);
         },

         get uploadSummary() {
            const total = this.uploads.length;
            const sent = this.uploads.filter(upload => upload.done).length;
            const failed = this.failedUploads.length;
            const plural = count => count > 1 ? 's' : '';
            if (this.uploading)
               return `Envoi en cours : ${sent} / ${total} photo${plural(total)}` + (failed ? ` · ${failed} échec${plural(failed)}` : '');
            if (failed)
               return `${sent} photo${plural(sent)} envoyée${plural(sent)}, ${failed} en échec`;
            return `${sent} photo${plural(sent)} envoyée${plural(sent)}`;
         },

         photoCount(count) {
            return count + ' photo' + (count > 1 ? 's' : '');
         },

         addFiles(files) {
            const isPublic = this.newPhotosPublic;
            Array.from(files).filter(file => file.type.startsWith('image/') || file.type === '').forEach(file => {
               this.uploads.push({ key: Math.random().toString(36).slice(2), file, name: file.name, isPublic, progress: 0, started: false, done: false, error: '' });
            });
            this.processQueue();
         },

         retryFailed() {
            this.failedUploads.forEach(upload => Object.assign(upload, { progress: 0, started: false, error: '' }));
            this.processQueue();
         },

         // Sends the photos a few at a time
         async processQueue() {
            if (this.uploading)
               return;
            this.uploading = true;
            const worker = async () => {
               let upload;
               while ((upload = this.uploads.find(item => !item.started))) {
                  upload.started = true;
                  await this.send(upload);
               }
            };
            await Promise.all(Array.from({ length: PARALLEL_UPLOADS }, worker));
            this.uploading = false;
         },

         async send(upload) {
            try {
               const file = await reducePhoto(upload.file);
               if (file.size > this.maxUploadBytes)
                  throw new Error('Photo trop lourde.');

               const data = new FormData();
               data.append('photo', file, upload.name.replace(/\.[^.]+$/, '') + '.jpg');
               data.append('is_public', upload.isPublic ? '1' : '0');

               const response = await sendWithProgress(this.uploadUrl, data, progress => upload.progress = progress);
               if (!response.success)
                  throw new Error(response.message || 'Erreur lors de l\'envoi.');

               upload.progress = 100;
               upload.done = true;
               this.photos.push(response.photo);
            } catch (error) {
               upload.progress = 100;
               upload.error = error.message || 'Erreur lors de l\'envoi.';
            }
         },

         isSelected(photo) {
            return this.selected.includes(photo.id);
         },

         // Shift + click selects every photo between the last clicked one and this one
         toggleSelection(photo, index, event) {
            const select = !this.isSelected(photo);
            const indexes = event.shiftKey && this.lastSelectedIndex !== null
               ? Array.from({ length: Math.abs(index - this.lastSelectedIndex) + 1 }, (_, i) => Math.min(index, this.lastSelectedIndex) + i)
               : [index];
            indexes.forEach(i => {
               const id = this.photos[i].id;
               if (select && !this.selected.includes(id)) this.selected.push(id);
               if (!select) this.selected = this.selected.filter(selectedId => selectedId !== id);
            });
            this.lastSelectedIndex = index;
         },

         selectAll() {
            this.selected = this.photos.map(photo => photo.id);
         },

         async bulk(action, ids = this.selected) {
            this.actionError = '';
            this.bulkBusy = true;
            try {
               const data = new FormData();
               data.append('action', action);
               ids.forEach(id => data.append('ids[]', id));
               const response = await fetch(this.bulkUrl, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
               const json = await response.json();
               if (!response.ok || !json.success)
                  throw new Error(json.message);

               if (action === 'delete')
                  this.photos = this.photos.filter(photo => !json.ids.includes(photo.id));
               else
                  this.photos.filter(photo => json.ids.includes(photo.id)).forEach(photo => photo.is_public = action === 'public');
               this.selected = this.selected.filter(id => !json.ids.includes(id));
               this.lastSelectedIndex = null;
            } catch (error) {
               this.actionError = error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.';
            } finally {
               this.bulkBusy = false;
            }
         },

         deletePhotos(photos) {
            const message = photos.length > 1 ? `Supprimer définitivement ces ${photos.length} photos ?` : 'Supprimer définitivement cette photo ?';
            if (confirm(message))
               this.bulk('delete', photos.map(photo => photo.id));
         },

         async toggleVisibility(photo) {
            this.actionError = '';
            photo.busy = true;
            try {
               const data = new FormData();
               data.append('is_public', photo.is_public ? '0' : '1');
               const response = await fetch(`${this.photoUrl}/${photo.id}/visibility`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
               const json = await response.json();
               if (!response.ok || !json.success)
                  throw new Error(json.message);
               photo.is_public = json.is_public;
            } catch (error) {
               this.actionError = error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.';
            } finally {
               photo.busy = false;
            }
         },
      }
   }

   // Returns a JPEG version of the photo, turned upright and reduced to MAX_PHOTO_SIZE px.
   // If the browser can not read the image (e.g. some HEIC files), the original file is sent and processed by the server.
   async function reducePhoto(file) {
      try {
         const bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });
         const ratio = Math.min(1, MAX_PHOTO_SIZE / Math.max(bitmap.width, bitmap.height));
         const canvas = document.createElement('canvas');
         canvas.width = Math.round(bitmap.width * ratio);
         canvas.height = Math.round(bitmap.height * ratio);
         const context = canvas.getContext('2d');
         context.fillStyle = '#fff';
         context.fillRect(0, 0, canvas.width, canvas.height);
         context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);
         bitmap.close();
         const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9));
         return blob || file;
      } catch (error) {
         return file;
      }
   }

   // XMLHttpRequest instead of fetch to follow the upload progress
   function sendWithProgress(url, data, onProgress) {
      return new Promise((resolve, reject) => {
         const request = new XMLHttpRequest();
         request.open('POST', url);
         request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
         request.upload.onprogress = event => {
            if (event.lengthComputable)
               onProgress(Math.min(95, Math.round(event.loaded / event.total * 95)));
         };
         request.onload = () => {
            try {
               resolve(JSON.parse(request.responseText));
            } catch (error) {
               reject(new Error(request.status === 413 ? 'Photo trop lourde pour le serveur.' : 'Réponse inattendue du serveur (session expirée ?).'));
            }
         };
         request.onerror = () => reject(new Error('Connexion impossible.'));
         request.send(data);
      });
   }
</script>
<?= $this->endSection() ?>
