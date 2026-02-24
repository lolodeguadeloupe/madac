<?php
$ticker_raw = get_option('madac_ticker_items', "Masterclass Gwoka\nFormation Danse Créole\nSéminaires Entreprises\nTeam Building\nConcerts Privés\nMasterclass Percussions");
$items = array_filter(array_map('trim', explode("\n", $ticker_raw)));
if (empty($items)) return;
// Duplicate for infinite scroll effect
$all_items = array_merge($items, $items);
?>
<div class="ticker">
  <div class="ticker-inner">
    <?php foreach ($all_items as $item) : ?>
    <span class="ticker-item"><?php echo esc_html($item); ?></span>
    <?php endforeach; ?>
  </div>
</div>
