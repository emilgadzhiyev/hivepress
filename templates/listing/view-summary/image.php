<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<div class="hp-listing__image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a></div>