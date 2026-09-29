<?php
   $value = fn(string $field) => old($field, null, false) ?? (string) $page->$field;
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= esc($page->title) ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="pageEditor(<?= esc(js_data($value('content')), 'attr') ?>)">
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Modifier : ' . $page->title, 'back' => ['/admin/pages', 'Charte et confidentialité']]) ?>
         <?php if ($url): ?>
            <?= component('button', ['label' => 'Voir la page', 'href' => $url, 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
         <?php endif; ?>
      <?= component_close() ?>

      <?= component('flash') ?>

      <form method="POST" action="<?= base_url('admin/pages/' . $page->slug) ?>" class="space-y-6 pb-16" @submit="dirty = false">
         <div class="max-w-3xl">
            <?= component('field', ['label' => 'Titre', 'name' => 'title', 'required' => true, 'value' => $value('title'), 'attrs' => ['maxlength' => 150]]) ?>
         </div>

         <!-- Mobile: tabs; large screens: text and preview side by side -->
         <div class="flex gap-x-2 lg:hidden" role="tablist">
            <?php foreach (['write' => 'Écrire', 'preview' => 'Aperçu'] as $tab => $label): ?>
               <?= component('chip', ['label' => $label, 'active_alpine' => "tab === '$tab'", 'attrs' => ['@click' => "tab = '$tab'" . ($tab === 'preview' ? '; refresh()' : '')]]) ?>
            <?php endforeach; ?>
         </div>
         <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div :class="tab === 'write' ? '' : 'hidden lg:block'">
               <label for="content" class="block text-sm font-medium leading-6 text-gray-900">Texte <span class="text-red-600">*</span></label>
               <textarea id="content" name="content" rows="28" required maxlength="60000" x-model="content" @input="changed()"
                  class="mt-2 block w-full rounded-md border-0 py-2 font-mono text-sm leading-6 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
               <details class="mt-3 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                  <summary class="cursor-pointer font-medium text-gray-900">Mise en forme du texte</summary>
                  <dl class="mt-3 grid grid-cols-[auto,1fr] gap-x-4 gap-y-2">
                     <dt class="font-mono">## Titre</dt><dd>Titre d'une partie (repris dans le sommaire)</dd>
                     <dt class="font-mono">### Sous-titre</dt><dd>Sous-titre</dd>
                     <dt class="font-mono">- élément</dt><dd>Liste à puces</dd>
                     <dt class="font-mono">**gras**</dt><dd>Texte en gras (*italique* avec une seule étoile)</dd>
                     <dt class="font-mono">[texte](https://...)</dt><dd>Lien (aussi /contact, mailto:...)</dd>
                     <dt class="font-mono">{email}</dt><dd>Adresse de contact de l'unité</dd>
                     <dt class="font-mono">ligne vide</dt><dd>Nouveau paragraphe</dd>
                  </dl>
               </details>
            </div>
            <div :class="tab === 'preview' ? '' : 'hidden lg:block'">
               <p class="text-sm font-medium leading-6 text-gray-900">Aperçu <span x-show="loading" class="font-normal text-gray-400">(mise à jour…)</span></p>
               <div class="page-text mt-2 max-h-[46rem] overflow-y-auto rounded-md p-4 ring-1 ring-inset ring-gray-200" x-html="html"></div>
               <p x-show="error" x-text="error" class="mt-2 text-sm text-red-600"></p>
            </div>
         </div>

         <?= component('form_actions', ['cancel' => '/admin/pages', 'submit' => 'Enregistrer']) ?>
      </form>
   </div>
</div>

<script>
   function pageEditor(content) {
      return {
         content: content,
         html: '',
         tab: 'write',
         loading: false,
         error: '',
         dirty: false,
         timer: null,

         init() {
            this.refresh();
            // Warns before leaving the page with unsaved changes
            window.addEventListener('beforeunload', event => { if (this.dirty) event.preventDefault(); });
         },

         changed() {
            this.dirty = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.refresh(), 400);
         },

         // The preview is made by the server, with the same rules as the published page
         async refresh() {
            this.loading = true;
            try {
               this.html = (await requestJson('<?= base_url('admin/pages/apercu') ?>', { content: this.content })).html;
               this.error = '';
            } catch (error) {
               this.error = 'L\'aperçu n\'a pas pu être mis à jour.';
            } finally {
               this.loading = false;
            }
         },
      };
   }
</script>
<?= $this->endSection() ?>
