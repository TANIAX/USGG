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

         <div class="mt-3 flex justify-center rounded-lg border-2 border-dashed px-6 py-8 transition-colors"
            :class="dragOver ? 'border-indigo-500 bg-indigo-50' : 'border-gray-900/25'"
            @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false"
            @drop.prevent="dragOver = false; addFiles($event.dataTransfer.files)">
            <div class="text-center">
               <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
               </svg>
               <label class="mt-4 inline-block cursor-pointer rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                  <span>Choisir des photos</span>
                  <input type="file" accept="image/*" multiple class="sr-only" @change="addFiles($event.target.files); $event.target.value = ''">
               </label>
               <p class="mt-2 hidden text-sm text-gray-600 sm:block">ou glissez-les ici</p>
               <p class="mt-1 text-xs text-gray-500">Plusieurs photos à la fois. Elles sont réduites avant l'envoi et leurs données de localisation sont supprimées.</p>
            </div>
         </div>

         <!-- Upload queue -->
         <ul class="mt-4 space-y-2" x-show="uploads.length > 0">
            <template x-for="upload in uploads" :key="upload.key">
               <li class="flex items-center gap-x-3 text-sm">
                  <span class="w-48 truncate text-gray-700 sm:w-72" x-text="upload.name"></span>
                  <div class="h-2 flex-auto overflow-hidden rounded-full bg-gray-200">
                     <div class="h-2 rounded-full transition-all" :style="`width: ${upload.progress}%`"
                        :class="upload.error ? 'bg-red-500' : (upload.done ? 'bg-green-500' : 'bg-indigo-600')"></div>
                  </div>
                  <span class="w-40 text-right text-xs" :class="upload.error ? 'text-red-600' : 'text-gray-500'"
                     x-text="upload.error || (upload.done ? 'Envoyée' : (upload.progress > 0 ? upload.progress + ' %' : 'En attente'))"></span>
               </li>
            </template>
         </ul>
         <button type="button" x-show="uploads.length > 0 && !uploading" @click="uploads = []"
            class="mt-2 text-sm font-medium text-gray-500 hover:text-gray-700">Effacer la liste</button>
      </section>

      <!-- Photos -->
      <section class="mb-10">
         <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Photos</h2>
            <div class="flex gap-x-4 text-sm" x-show="photos.length > 0">
               <form method="POST" action="<?= base_url('admin/galerie/album/' . $album->id . '/visibility') ?>">
                  <input type="hidden" name="is_public" value="1">
                  <button type="submit" class="font-semibold text-indigo-600 hover:text-indigo-500">Tout rendre visible sans compte</button>
               </form>
               <form method="POST" action="<?= base_url('admin/galerie/album/' . $album->id . '/visibility') ?>">
                  <input type="hidden" name="is_public" value="0">
                  <button type="submit" class="font-semibold text-gray-600 hover:text-gray-800">Tout réserver aux personnes connectées</button>
               </form>
            </div>
         </div>
         <p class="mt-1 text-sm text-gray-500">Cliquez sur le cadenas d'une photo pour la rendre visible sans compte ou la réserver aux personnes connectées.</p>

         <ul role="list" class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <template x-for="photo in photos" :key="photo.id">
               <li class="relative overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                  <a :href="`/galerie/photo/${photo.id}`" target="_blank">
                     <img :src="`/galerie/photo/${photo.id}/miniature`" alt="" class="aspect-square w-full object-cover" loading="lazy"
                        :class="photo.is_public ? '' : 'opacity-80'">
                  </a>
                  <div class="flex items-center justify-between gap-x-2 bg-white px-2 py-1.5">
                     <button type="button" @click="toggleVisibility(photo)" :disabled="!!photo.busy"
                        class="inline-flex items-center gap-x-1 rounded px-1.5 py-0.5 text-xs font-medium"
                        :class="photo.is_public ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
                        :title="photo.is_public ? 'Visible sans compte : cliquer pour la réserver aux personnes connectées' : 'Réservée aux personnes connectées : cliquer pour la rendre visible sans compte'">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                           <path x-show="!photo.is_public" fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                           <path x-show="photo.is_public" fill-rule="evenodd" d="M14.5 1A4.5 4.5 0 0010 5.5V9H3a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-1.5V5.5a3 3 0 116 0v2.75a.75.75 0 001.5 0V5.5A4.5 4.5 0 0014.5 1z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="photo.is_public ? 'Publique' : 'Privée'"></span>
                     </button>
                     <button type="button" @click="deletePhoto(photo)" :disabled="!!photo.busy" class="text-xs font-medium text-red-600 hover:text-red-800">
                        Supprimer
                     </button>
                  </div>
               </li>
            </template>
         </ul>
         <p x-show="photos.length === 0" class="mt-4 text-sm text-gray-500">Aucune photo pour l'instant.</p>
         <p x-show="actionError" x-text="actionError" class="mt-4 text-sm text-red-600"></p>
      </section>

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

   function app() {
      return {
         uploadUrl: '<?= base_url('admin/galerie/album/' . $album->id . '/upload') ?>',
         photoUrl: '<?= rtrim(base_url('admin/galerie/photo'), '/') ?>',
         maxUploadBytes: <?= (int) $maxUploadKb ?> * 1024,
         photos: <?= $photos ?>,
         newPhotosPublic: true,
         dragOver: false,
         uploads: [],
         uploading: false,
         actionError: '',

         get privateCount() {
            return this.photos.filter(photo => !photo.is_public).length;
         },

         photoCount(count) {
            return count + ' photo' + (count > 1 ? 's' : '');
         },

         addFiles(files) {
            const isPublic = this.newPhotosPublic;
            Array.from(files).filter(file => file.type.startsWith('image/') || file.type === '').forEach(file => {
               this.uploads.push({ key: Math.random().toString(36).slice(2), file, name: file.name, isPublic, progress: 0, done: false, error: '' });
            });
            this.processQueue();
         },

         // Sends the photos two by two
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
            await Promise.all([worker(), worker()]);
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

         async toggleVisibility(photo) {
            await this.photoAction(photo, 'visibility', { is_public: photo.is_public ? '0' : '1' }, response => photo.is_public = response.is_public);
         },

         async deletePhoto(photo) {
            if (!confirm('Supprimer définitivement cette photo ?'))
               return;
            await this.photoAction(photo, 'delete', {}, () => this.photos = this.photos.filter(item => item.id !== photo.id));
         },

         async photoAction(photo, action, fields, onSuccess) {
            this.actionError = '';
            photo.busy = true;
            try {
               const data = new FormData();
               Object.entries(fields).forEach(([key, value]) => data.append(key, value));
               const response = await fetch(`${this.photoUrl}/${photo.id}/${action}`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
               const json = await response.json();
               if (!response.ok || !json.success)
                  throw new Error(json.message);
               onSuccess(json);
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
