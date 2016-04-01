<?php if ($homepages) { ?>
  <?php if ($layout_option == 'tabbed') { ?>
    <div id="tabs" class="htabs">
      <?php for ($i = 0; $i < sizeof($homepages); $i++) { ?>
      <a href="#tab_<?php echo $i; ?>"><?php echo $homepages[$i]['title']; ?></a>
      <?php } ?>
    </div>
    <?php for ($i = 0; $i < sizeof($homepages); $i++) { ?>
      <div id="tab_<?php echo $i; ?>" class="tab-content" style="min-height: <?php echo $min_height; ?>px;">
        <?php if ($homepages[$i]['image']) { ?>
        <img align="left" src="<?php echo $homepages[$i]['image']; ?>" title="<?php echo $homepages[$i]['title']; ?>" alt="<?php echo $homepages[$i]['title']; ?>" />
        <?php } ?>
        <h3><?php echo $homepages[$i]['title']; ?></h3>
        <?php echo $homepages[$i]['description']; ?>
      </div>
    <?php } ?>
  <?php } elseif ($layout_option == 'grid') { ?>
    <div class="homepage-grid">
      <table class="list">
      <?php for ($i = 0; $i < sizeof($homepages); $i = $i + $cols) { ?>
        <tr>
          <?php for ($j = $i; $j < ($i + $cols); $j++) { ?>
          <td style="width: <?php echo $col_width; ?>%;"><?php if (isset($homepages[$j])) { ?>
            <?php if ($homepages[$j]['image']) { ?>
            <img align="left" src="<?php echo $homepages[$j]['image']; ?>" title="<?php echo $homepages[$j]['title']; ?>" alt="<?php echo $homepages[$j]['title']; ?>" />
            <?php } ?>
            <h3><?php echo $homepages[$j]['title']; ?></h3>
            <?php echo $homepages[$j]['description']; ?>
          <?php } ?></td>
          <?php } ?>
        </tr>
        <?php } ?>
      </table>
    </div>
  <?php } else { ?>
    <?php foreach ($homepages as $homepage) { ?>
    <div class="homepage">
      <?php if ($homepage['image']) { ?>
      <img align="left" src="<?php echo $homepage['image']; ?>" title="<?php echo $homepage['title']; ?>" alt="<?php echo $homepage['title']; ?>" />
      <?php } ?>
      <h3><?php echo $homepage['title']; ?></h3>
      <?php echo $homepage['description']; ?>
    </div>
    <?php } ?>
  <?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
