<?php
/**
 * Automatic carousel of the photos of an album cover (Alpine component coverCarousel of script.js). Props:
 *  - ids: ids of the photos (array, rendered by the server) or ids_alpine: javascript expression of the ids
 *  - class: classes of the carousel (it fills its container)
 */
$idsExpression = isset($ids_alpine) ? $ids_alpine : json_encode(array_values(array_map('intval', $ids ?? [])));
?>
<div<?= attrs(['class' => classes('relative h-full w-full', $class), 'x-data' => "coverCarousel($idsExpression)"] + $attrs) ?>>
   <template x-for="(id, index) in ids" :key="id">
      <template x-if="loaded.includes(index)">
         <img :src="url(id)" alt="" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000"
            :class="index === current ? 'opacity-100' : 'opacity-0'">
      </template>
   </template>
   <!-- Position in the carousel -->
   <div x-show="ids.length > 1" class="absolute inset-x-0 bottom-2 flex justify-center gap-1.5" aria-hidden="true">
      <template x-for="(id, index) in ids" :key="id">
         <span class="h-1.5 w-1.5 rounded-full shadow transition-colors" :class="index === current ? 'bg-white' : 'bg-white/50'"></span>
      </template>
   </div>
</div>
