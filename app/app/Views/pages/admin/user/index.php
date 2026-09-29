<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Utilisateurs
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Utilisateurs', 'subtitle' => 'Comptes du site et rôles d\'administration.']) ?>
         <?= component('button', ['label' => 'Créer un compte', 'href' => '/admin/utilisateurs/create', 'size' => 'sm', 'block' => true]) ?>
      <?= component_close() ?>

      <?= component('flash') ?>

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
            <?= component('chip', ['active_alpine' => "role === ''", 'attrs' => ['@click' => "role = ''", 'x-text' => "`Tous (\${counts[''] ?? 0})`"]]) ?>
            <?php foreach ($roles as $code => $role): ?>
               <?= component('chip', ['active_alpine' => "role === '$code'", 'attrs' => ['@click' => "role = '$code'", 'x-text' => "`" . addslashes($role['label']) . " (\${counts['$code'] ?? 0})`"]]) ?>
            <?php endforeach; ?>
            <?= component('chip', ['tone' => 'dark', 'active_alpine' => "role === 'inactive'", 'attrs' => ['@click' => "role = 'inactive'", 'x-text' => '`Désactivés (${counts.inactive ?? 0})`']]) ?>
         </div>
         <?= component('search', ['placeholder' => 'Nom, totem ou e-mail', 'class' => 'lg:w-80']) ?>
      </div>

      <ul id="list-top" role="list" class="scroll-mt-8 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200 transition-opacity" :class="listLoading ? 'opacity-60' : ''" x-show="items.length > 0">
         <template x-for="user in items" :key="user.id">
            <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-3" :class="user.exists ? '' : 'bg-gray-50'">
               <div class="min-w-0 flex-auto">
                  <p class="font-semibold" :class="user.exists ? 'text-gray-900' : 'text-gray-400'">
                     <span x-text="user.display_name"></span>
                     <span class="font-normal text-gray-500" x-show="user.totem" x-text="`(${user.totem})`"></span>
                     <?= component('badge', ['label' => 'vous', 'color' => 'indigo', 'shape' => 'tag', 'class' => 'ml-1', 'attrs' => ['x-show' => 'user.id === currentUserId']]) ?>
                     <?= component('badge', ['label' => 'désactivé', 'color' => 'gray-dark', 'shape' => 'tag', 'class' => 'ml-1', 'attrs' => ['x-show' => '!user.exists']]) ?>
                  </p>
                  <p class="truncate text-sm text-gray-500" x-text="user.email"></p>
               </div>
               <div class="flex flex-wrap gap-1.5">
                  <template x-for="code in user.roles.filter(r => roles[r])" :key="code">
                     <?= component('badge', ['color' => 'none', 'attrs' => [':class' => "code === 'super_admin' ? 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-200' : 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200'", 'x-text' => 'roles[code].label']]) ?>
                  </template>
                  <span x-show="!user.roles.some(r => roles[r])" class="text-xs text-gray-400">Compte simple</span>
               </div>
               <a :href="`/admin/utilisateurs/edit/${user.id}`" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Modifier</a>
            </li>
         </template>
      </ul>
      <?= component('pagination', ['noun' => ['compte', 'comptes'], 'class' => 'mt-6']) ?>
      <?= component('empty_state', ['icon' => 'users', 'title' => 'Aucun compte ne correspond.', 'attrs' => ['x-show' => 'items.length === 0']]) ?>
      <div class="h-16"></div>
   </div>
</div>

<script>
   function app() {
      return listApp('/admin/utilisateurs', <?= $list ?>, {
         roles: <?= js_data($roles) ?>,
         currentUserId: <?= (int) $currentUserId ?>,
      });
   }
</script>
<?= $this->endSection() ?>
