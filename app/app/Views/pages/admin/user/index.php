<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Utilisateurs
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <div class="sm:flex justify-start sm:items-center mt-12 mb-8 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-tight text-gray-900">Utilisateurs</h1>
            <p class="mt-1 text-sm text-gray-500">Comptes du site et rôles d'administration.</p>
         </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="/admin/utilisateurs/create" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Créer un compte</a>
         </div>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <!-- What the roles allow -->
      <details class="mb-6 rounded-lg bg-gray-50 p-4 text-sm">
         <summary class="cursor-pointer font-medium text-gray-900">Que permet chaque rôle ?</summary>
         <dl class="mt-3 grid gap-3 sm:grid-cols-2">
            <?php foreach ($roles as $role): ?>
               <div><dt class="font-medium text-gray-900"><?= esc($role['label']) ?></dt><dd class="text-gray-600"><?= esc($role['description']) ?></dd></div>
            <?php endforeach; ?>
            <div><dt class="font-medium text-gray-900">Sans rôle</dt><dd class="text-gray-600">Compte simple : voit les photos réservées aux membres.</dd></div>
         </dl>
      </details>

      <!-- Filters -->
      <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
         <div class="flex flex-wrap gap-2 text-sm">
            <button type="button" @click="role = ''" class="rounded-full px-3 py-1 font-medium ring-1 ring-inset" :class="role === '' ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300'" x-text="`Tous (${users.length})`"></button>
            <template x-for="(definition, code) in roles" :key="code">
               <button type="button" @click="role = code" class="rounded-full px-3 py-1 font-medium ring-1 ring-inset" :class="role === code ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300'"
                  x-text="`${definition.label} (${users.filter(u => u.roles.includes(code)).length})`"></button>
            </template>
            <button type="button" @click="role = 'inactive'" class="rounded-full px-3 py-1 font-medium ring-1 ring-inset" :class="role === 'inactive' ? 'bg-gray-900 text-white ring-gray-900' : 'bg-white text-gray-700 ring-gray-300'" x-text="`Désactivés (${users.filter(u => !u.exists).length})`"></button>
         </div>
         <div class="lg:w-80">
            <label for="search" class="sr-only">Recherche</label>
            <input type="text" id="search" x-model="search" placeholder="Nom, totem ou e-mail"
               class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
         </div>
      </div>

      <ul role="list" class="divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200" x-show="filteredUsers.length > 0">
         <template x-for="user in filteredUsers" :key="user.id">
            <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-3" :class="user.exists ? '' : 'bg-gray-50'">
               <div class="min-w-0 flex-auto">
                  <p class="font-semibold" :class="user.exists ? 'text-gray-900' : 'text-gray-400'">
                     <span x-text="user.display_name"></span>
                     <span class="font-normal text-gray-500" x-show="user.totem" x-text="`(${user.totem})`"></span>
                     <span x-show="user.id === currentUserId" class="ml-1 rounded bg-indigo-50 px-1.5 py-0.5 text-xs font-medium text-indigo-700">vous</span>
                     <span x-show="!user.exists" class="ml-1 rounded bg-gray-200 px-1.5 py-0.5 text-xs font-medium text-gray-600">désactivé</span>
                  </p>
                  <p class="truncate text-sm text-gray-500" x-text="user.email"></p>
               </div>
               <div class="flex flex-wrap gap-1.5">
                  <template x-for="code in user.roles.filter(r => roles[r])" :key="code">
                     <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="code === 'super_admin' ? 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-200' : 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200'" x-text="roles[code].label"></span>
                  </template>
                  <span x-show="!user.roles.some(r => roles[r])" class="text-xs text-gray-400">Compte simple</span>
               </div>
               <a :href="`/admin/utilisateurs/edit/${user.id}`" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Modifier</a>
            </li>
         </template>
      </ul>
      <p x-show="filteredUsers.length === 0" class="py-10 text-center text-sm text-gray-500">Aucun compte ne correspond.</p>
      <div class="h-16"></div>
   </div>
</div>

<script>
   function app() {
      return {
         users: <?= $users ?>,
         roles: <?= json_encode($roles, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
         currentUserId: <?= (int) $currentUserId ?>,
         role: '',
         search: '',

         get filteredUsers() {
            const search = this.search.trim().toLowerCase();
            return this.users.filter(user =>
               (this.role === '' || (this.role === 'inactive' ? !user.exists : user.roles.includes(this.role)))
               && (!search || [user.display_name, user.totem || '', user.email].some(text => text.toLowerCase().includes(search))));
         },
      }
   }
</script>
<?= $this->endSection() ?>
