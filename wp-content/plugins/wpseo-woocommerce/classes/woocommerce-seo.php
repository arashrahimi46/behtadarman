<?php
/**
 * WooCommerce Yoast SEO plugin file.
 *
 * @package WPSEO/WooCommerce
 */

/**
 * Class Yoast_WooCommerce_SEO
 */
class Yoast_WooCommerce_SEO {

	/**
	 * Version of the plugin.
	 *
	 * @var string
	 */
	const VERSION = WPSEO_WOO_VERSION;

	/**
	 * Return the plugin file.
	 *
	 * @return string
	 */
	public static function get_plugin_file() {
		return WPSEO_WOO_PLUGIN_FILE;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->initialize();
	}

	/**
	 * Initializes the plugin, basically hooks all the required functionality.
	 *
	 * @since 7.0
	 *
	 * @return void
	 */
	protected function initialize() {
		if ( $this->is_woocommerce_page( filter_input( INPUT_GET, 'page' ) ) ) {
			$this->register_i18n_promo_class();
		}

		// Make sure the options property is always current.
		add_action( 'init', [ 'WPSEO_Option_Woo', 'register_option' ] );

		// Enable Yoast usage tracking.
		add_filter( 'wpseo_enable_tracking', '__return_true' );

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			// Add subitem to menu.
			add_filter( 'wpseo_submenu_pages', [ $this, 'add_submenu_pages' ] );
			add_action( 'admin_print_styles', [ $this, 'config_page_styles' ] );

			// Products tab columns.
			add_filter( 'manage_product_posts_columns', [ $this, 'column_heading' ], 11, 1 );

			// Move Woo box above SEO box.
			add_action( 'admin_footer', [ $this, 'footer_js' ] );

			new WPSEO_WooCommerce_Yoast_Tab();
		}
		else {
			// Initialize schema.
			add_action( 'init', [ $this, 'initialize_schema' ] );

			// Add metadescription filter.
			add_filter( 'wpseo_metadesc', [ $this, 'metadesc' ] );

			// OpenGraph.
			add_filter( 'language_attributes', [ $this, 'og_product_namespace' ], 11 );
			add_filter( 'wpseo_opengraph_type', [ $this, 'return_type_product' ] );
			add_filter( 'wpseo_opengraph_desc', [ $this, 'og_desc_enhancement' ] );
			add_action( 'wpseo_opengraph', [ $this, 'og_enhancement' ], 50 );
			add_action( 'wpseo_register_extra_replacements', [ $this, 'register_replacements' ] );

			if ( class_exists( 'WPSEO_OpenGraph_Image' ) ) {
				add_action( 'wpseo_add_opengraph_additional_images', [ $this, 'set_opengraph_image' ] );
			}

			add_filter( 'wpseo_sitemap_exclude_post_type', [ $this, 'xml_sitemap_post_types' ], 10, 2 );
			add_filter( 'wpseo_sitemap_post_type_archive_link', [ $this, 'xml_sitemap_taxonomies' ], 10, 2 );
			add_filter( 'wpseo_sitemap_page_for_post_type_archive', [ $this, 'xml_post_type_archive_page_id' ], 10, 2 );

			add_filter( 'post_type_archive_link', [ $this, 'xml_post_type_archive_link' ], 10, 2 );
			add_filter( 'wpseo_sitemap_urlimages', [ $this, 'add_product_images_to_xml_sitemap' ], 10, 2 );

			// Fix breadcrumbs.
			add_action( 'send_headers', [ $this, 'handle_breadcrumbs_replacements' ] );
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Make sure the primary category will be used in the permalink.
		add_filter( 'wc_product_post_type_link_product_cat', [ $this, 'add_primary_category_permalink' ], 10, 3 );

		// Adds recommended replacevars.
		add_filter( 'wpseo_recommended_replace_vars', [ $this, 'add_recommended_replacevars' ] );

		add_action( 'admin_init', [ $this, 'init_beacon' ] );

		add_filter( 'wpseo_sitemap_entry', [ $this, 'filter_hidden_product' ], 10, 3 );
		add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', [ $this, 'filter_woocommerce_pages' ] );
	}

	/**
	 * Initializes the schema functionality.
	 */
	public function initialize_schema() {
		if ( WPSEO_WooCommerce_Schema::should_output_yoast_schema() ) {
			new WPSEO_WooCommerce_Schema();
		}
	}

	/**
	 * Prevents a hidden product from being added to the sitemap.
	 *
	 * @param array   $url  The url data.
	 * @param string  $type The object type.
	 * @param WP_Post $post The post object.
	 *
	 * @return bool|array False when entry is hidden.
	 */
	public function filter_hidden_product( $url, $type, $post ) {
		if ( empty( $url['loc'] ) ) {
			return $url;
		}

		if ( ! is_object( $post ) || ! property_exists( $post, 'post_type' ) ) {
			return $url;
		}

		if ( $post->post_type !== 'product' ) {
			return $url;
		}

		$excluded_from_catalog = $this->excluded_from_catalog();
		if ( in_array( $post->ID, $excluded_from_catalog, true ) ) {
			return false;
		}

		return $url;
	}

	/**
	 * Retrieves the products that are excluded from the catalog.
	 *
	 * @return array Excluded product ids.
	 */
	protected function excluded_from_catalog() {
		static $excluded_from_catalog;

		if ( $excluded_from_catalog === null ) {
			$query                 = new WP_Query(
				[
					'fields'         => 'ids',
					'posts_per_page' => '-1',
					'post_type'      => 'product',
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					'tax_query'      => [
						[
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => [ 'exclude-from-catalog' ],
						],
					],
				]
			);
			$excluded_from_catalog = $query->get_posts();
		}

		return $excluded_from_catalog;
	}

	/**
	 * Adds the page ids from the WooCommerce core pages to the excluded post ids.
	 *
	 * @param array $excluded_posts_ids The excluded post ids.
	 *
	 * @return array The post ids with the added page ids.
	 */
	public function filter_woocommerce_pages( $excluded_posts_ids ) {
		$woocommerce_pages   = [];
		$woocommerce_pages[] = wc_get_page_id( 'cart' );
		$woocommerce_pages[] = wc_get_page_id( 'checkout' );
		$woocommerce_pages[] = wc_get_page_id( 'myaccount' );
		$woocommerce_pages   = array_filter( $woocommerce_pages );

		return array_merge( $excluded_posts_ids, $woocommerce_pages );
	}

	/**
	 * Adds the recommended WooCommerce replacevars to Yoast SEO.
	 *
	 * @param array $replacevars Array with replacevars.
	 *
	 * @return array Array with the added replacevars.
	 */
	public function add_recommended_replacevars( $replacevars ) {
		if ( ! class_exists( 'WooCommerce', false ) ) {
			return $replacevars;
		}

		$replacevars['product']                = [ 'sitename', 'title', 'sep', 'primary_category' ];
		$replacevars['product_cat']            = [ 'sitename', 'term_title', 'sep' ];
		$replacevars['product_tag']            = [ 'sitename', 'term_title', 'sep' ];
		$replacevars['product_shipping_class'] = [ 'sitename', 'term_title', 'sep', 'page' ];
		$replacevars['product_brand']          = [ 'sitename', 'term_title', 'sep' ];
		$replacevars['pwb-brand']              = [ 'sitename', 'term_title', 'sep' ];
		$replacevars['product_archive']        = [ 'sitename', 'sep', 'page', 'pt_plural' ];

		return $replacevars;
	}

	/**
	 * Makes sure the primary category is used in the permalink.
	 *
	 * @param WP_Term   $term  The first found term belonging to the post.
	 * @param WP_Term[] $terms Array with all the terms belonging to the post.
	 * @param WP_Post   $post  The current open post.
	 *
	 * @return WP_Term
	 */
	public function add_primary_category_permalink( $term, $terms, $post ) {
		$primary_term    = new WPSEO_Primary_Term( 'product_cat', $post->ID );
		$primary_term_id = $primary_term->get_primary_term();

		if ( $primary_term_id ) {
			return get_term( $primary_term_id, 'product_cat' );
		}

		return $term;
	}

	/**
	 * Overrides the Woo breadcrumb functionality when the WP SEO breadcrumb functionality is enabled.
	 *
	 * @uses  woo_breadcrumbs filter
	 *
	 * @since 1.1.3
	 *
	 * @return string
	 */
	public function override_woo_breadcrumbs() {
		$breadcrumb = yoast_breadcrumb( '<div class="breadcrumb breadcrumbs woo-breadcrumbs"><div class="breadcrumb-trail">', '</div></div>', false );
		if ( current_action() === 'storefront_before_content' ) {
			$breadcrumb = '<div class="storefront-breadcrumb"><div class="col-full">' . $breadcrumb . '</div></div>';
		}
		return $breadcrumb;
	}

	/**
	 * Shows the Yoast SEO breadcrumbs.
	 *
	 * @return void
	 */
	public function show_yoast_breadcrumbs() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to output HTML. If we escape this we break it.
		echo $this->override_woo_breadcrumbs();
	}

	/**
	 * Add the selected attribute to the breadcrumb.
	 *
	 * @param array $crumbs Existing breadcrumbs.
	 *
	 * @return array
	 */
	public function add_attribute_to_breadcrumbs( $crumbs ) {
		global $_chosen_attributes;

		// Copy the array.
		$yoast_chosen_attributes = $_chosen_attributes;

		// Check if the attribute filter is used.
		if ( is_array( $yoast_chosen_attributes ) && count( $yoast_chosen_attributes ) > 0 ) {
			// Store keys.
			$att_keys = array_keys( $yoast_chosen_attributes );

			// We got an attribute filter, get the first Attribute.
			$att_group = array_shift( $yoast_chosen_attributes );

			if ( is_array( $att_group['terms'] ) && count( $att_group['terms'] ) > 0 ) {

				// Get the attribute ID.
				$att = array_shift( $att_group['terms'] );

				// Get the term.
				$term = get_term( (int) $att, array_shift( $att_keys ) );

				if ( is_object( $term ) ) {
					$crumbs[] = [
						'term' => $term,
					];
				}
			}
		}

		return $crumbs;
	}

	/**
	 * Add the product gallery images to the XML sitemap.
	 *
	 * @param array $images  The array of images for the post.
	 * @param int   $post_id The ID of the post object.
	 *
	 * @return array
	 */
	public function add_product_images_to_xml_sitemap( $images, $post_id ) {
		if ( metadata_exists( 'post', $post_id, '_product_image_gallery' ) ) {
			$product_image_gallery = get_post_meta( $post_id, '_product_image_gallery', true );

			$attachments = array_filter( explode( ',', $product_image_gallery ) );

			foreach ( $attachments as $attachment_id ) {
				$image_src = wp_get_attachment_image_src( $attachment_id );
				$image     = [
					// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- Using WPSEO hook.
					'src'   => apply_filters( 'wpseo_xml_sitemap_img_src', $image_src[0], $post_id ),
					'title' => get_the_title( $attachment_id ),
					'alt'   => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
				];
				$images[]  = $image;

				unset( $image, $image_src );
			}
		}

		return $images;
	}

	/**
	 * Registers the settings page in the WP SEO menu.
	 *
	 * @since 5.6
	 *
	 * @param array $submenu_pages List of current submenus.
	 *
	 * @return array All submenu pages including our own.
	 */
	public function add_submenu_pages( $submenu_pages ) {
		$submenu_pages[] = [
			'wpseo_dashboard',
			sprintf(
			/* translators: %1$s resolves to WooCommerce SEO */
				esc_html__( '%1$s Settings', 'yoast-woo-seo' ),
				'WooCommerce SEO'
			),
			'WooCommerce SEO',
			'wpseo_manage_options',
			'wpseo_woo',
			[ $this, 'admin_panel' ],
		];

		return $submenu_pages;
	}

	/**
	 * Loads CSS.
	 *
	 * @since 1.0
	 */
	public function config_page_styles() {
		global $pagenow;

		$is_wpseo_woocommerce_page = ( $pagenow === 'admin.php' && filter_input( INPUT_GET, 'page' ) === 'wpseo_woo' );
		if ( ! $is_wpseo_woocommerce_page ) {
			return;
		}

		if ( ! class_exists( 'WPSEO_Admin_Asset_Manager' ) ) {
			return;
		}

		$asset_manager = new WPSEO_Admin_Asset_Manager();
		$asset_manager->enqueue_style( 'admin-css' );
	}

	/**
	 * Builds the admin page.
	 *
	 * @since 1.0
	 */
	public function admin_panel() {
		Yoast_Form::get_instance()->admin_header( true, 'wpseo_woo' );

		$object_taxonomies = array_filter( get_object_taxonomies( 'product', 'objects' ), 'is_taxonomy_viewable' );
		$taxonomies        = [ '' => '-' ];
		foreach ( $object_taxonomies as $object_taxonomy ) {
			$taxonomies[ strtolower( $object_taxonomy->name ) ] = esc_html( $object_taxonomy->labels->name );
		}

		echo '<h2>' . esc_html__( 'Schema & OpenGraph additions', 'yoast-woo-seo' ) . '</h2>
		<p>' . esc_html__( 'If you have product attributes for the following types, select them here, the plugin will make sure they\'re used for the appropriate Schema.org and OpenGraph markup.', 'yoast-woo-seo' ) . '</p>';

		Yoast_Form::get_instance()->select( 'woo_schema_manufacturer', esc_html__( 'Manufacturer', 'yoast-woo-seo' ), $taxonomies );
		Yoast_Form::get_instance()->select( 'woo_schema_brand', esc_html__( 'Brand', 'yoast-woo-seo' ), $taxonomies );

		if ( WPSEO_Options::get( 'breadcrumbs-enable' ) === true ) {
			echo '<h2>' . esc_html__( 'Breadcrumbs', 'yoast-woo-seo' ) . '</h2>';
			echo '<p>';
			printf(
			/* translators: %1$s resolves to internal links options page, %2$s resolves to closing link tag, %3$s resolves to Yoast SEO, %4$s resolves to WooCommerce */
				esc_html__( 'Both %4$s and %3$s have breadcrumbs functionality. The %3$s breadcrumbs have a slightly higher chance of being picked up by search engines and you can configure them a bit more, on the %1$sBreadcrumbs settings page%2$s. To enable them, check the box below and the WooCommerce breadcrumbs will be replaced.', 'yoast-woo-seo' ),
				'<a href="' . esc_url( admin_url( 'admin.php?page=wpseo_titles#top#breadcrumbs' ) ) . '">',
				'</a>',
				'Yoast SEO',
				'WooCommerce'
			);
			echo "</p>\n";

			Yoast_Form::get_instance()->checkbox(
				'woo_breadcrumbs',
				sprintf(
				/* translators: %1$s resolves to WooCommerce */
					esc_html__( 'Replace %1$s Breadcrumbs', 'yoast-woo-seo' ),
					'WooCommerce'
				)
			);
		}

		echo '<h2>' . esc_html__( 'Admin', 'yoast-woo-seo' ) . '</h2>';
		echo '<p>';
		printf(
		/* translators: %1$s resolves to Yoast SEO, %2$s resolves to WooCommerce */
			esc_html__( 'Both %2$s and %1$s add columns to the product page, to remove all but the SEO score column from %1$s on that page, check this box.', 'yoast-woo-seo' ),
			'Yoast SEO',
			'WooCommerce'
		);
		echo "</p>\n";

		Yoast_Form::get_instance()->checkbox(
			'woo_hide_columns',
			sprintf(
			/* translators: %1$s resolves to Yoast SEO */
				esc_html__( 'Remove %1$s columns', 'yoast-woo-seo' ),
				'Yoast SEO'
			)
		);

		echo '<p>';
		printf(
		/* translators: %1$s resolves to Yoast SEO, %2$s resolves to WooCommerce */
			esc_html__( 'Both %2$s and %1$s add metaboxes to the edit product page, if you want %2$s to be above %1$s, check the box.', 'yoast-woo-seo' ),
			'Yoast SEO',
			'WooCommerce'
		);
		echo "</p>\n";

		Yoast_Form::get_instance()->checkbox(
			'woo_metabox_top',
			sprintf(
			/* translators: %1$s resolves to WooCommerce */
				esc_html__( 'Move %1$s up', 'yoast-woo-seo' ),
				'WooCommerce'
			)
		);

		// Submit button and debug info.
		Yoast_Form::get_instance()->admin_footer( true, false );
	}

	/**
	 * Adds a bit of JS that moves the meta box for WP SEO below the WooCommerce box.
	 *
	 * @since 1.0
	 */
	public function footer_js() {
		if ( WPSEO_Options::get( 'woo_metabox_top' ) !== true ) {
			return;
		}
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				// Show WooCommerce box before WP SEO metabox.
				if ( $( "#woocommerce-product-data" ).length > 0 && $( "#wpseo_meta" ).length > 0 ) {
					$( "#woocommerce-product-data" ).insertBefore( $( "#wpseo_meta" ) );
				}
			} );
		</script>
		<?php
	}

	/**
	 * Removes the Yoast SEO columns in the edit products page.
	 *
	 * @since 1.0
	 *
	 * @param array $columns List of registered columns.
	 *
	 * @return array Array with the filtered columns.
	 */
	public function column_heading( $columns ) {
		if ( WPSEO_Options::get( 'woo_hide_columns' ) !== true ) {
			return $columns;
		}

		$keys_to_remove = [
			'wpseo-title',
			'wpseo-metadesc',
			'wpseo-focuskw',
			'wpseo-score',
			'wpseo-score-readability',
		];

		if ( class_exists( 'WPSEO_Link_Columns' ) ) {
			$keys_to_remove[] = 'wpseo-' . WPSEO_Link_Columns::COLUMN_LINKS;
			$keys_to_remove[] = 'wpseo-' . WPSEO_Link_Columns::COLUMN_LINKED;
		}

		foreach ( $keys_to_remove as $key_to_remove ) {
			unset( $columns[ $key_to_remove ] );
		}

		return $columns;
	}

	/**
	 * Output WordPress SEO crafted breadcrumbs, instead of WooCommerce ones.
	 *
	 * @since 1.0
	 */
	public function woo_wpseo_breadcrumbs() {
		yoast_breadcrumb( '<nav class="woocommerce-breadcrumb">', '</nav>' );
	}

	/**
	 * Make sure product variations and shop coupons are not included in the XML sitemap.
	 *
	 * @since 1.0
	 *
	 * @param bool   $bool      Whether or not to include this post type in the XML sitemap.
	 * @param string $post_type The post type of the post.
	 *
	 * @return bool
	 */
	public function xml_sitemap_post_types( $bool, $post_type ) {
		if ( $post_type === 'product_variation' || $post_type === 'shop_coupon' ) {
			return true;
		}

		return $bool;
	}

	/**
	 * Make sure product attribute taxonomies are not included in the XML sitemap.
	 *
	 * @since 1.0
	 *
	 * @param bool   $bool     Whether or not to include this post type in the XML sitemap.
	 * @param string $taxonomy The taxonomy to check against.
	 *
	 * @return bool
	 */
	public function xml_sitemap_taxonomies( $bool, $taxonomy ) {
		if ( $taxonomy === 'product_type' || $taxonomy === 'product_shipping_class' || $taxonomy === 'shop_order_status' ) {
			return true;
		}

		if ( substr( $taxonomy, 0, 3 ) === 'pa_' ) {
			return true;
		}

		return $bool;
	}

	/**
	 * Filter for the namespace, adding the OpenGraph namespace.
	 *
	 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/product/
	 *
	 * @param string $input The input namespace string.
	 *
	 * @return string
	 */
	public function og_product_namespace( $input ) {
		if ( is_singular( 'product' ) ) {
			$input = preg_replace( '/prefix="([^"]+)"/', 'prefix="$1 product: http://ogp.me/ns/product#"', $input );
		}

		return $input;
	}

	/**
	 * Adds the opengraph images.
	 *
	 * @since 4.3
	 *
	 * @param WPSEO_OpenGraph_Image $opengraph_image The OpenGraph image to use.
	 */
	public function set_opengraph_image( WPSEO_OpenGraph_Image $opengraph_image ) {

		if ( ! function_exists( 'is_product_category' ) || is_product_category() ) {
			global $wp_query;
			$cat          = $wp_query->get_queried_object();
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$img_url      = wp_get_attachment_url( $thumbnail_id );
			if ( $img_url ) {
				$opengraph_image->add_image( $img_url );
			}
		}

		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return;
		}

		$img_ids = $this->get_image_ids( $product );

		if ( is_array( $img_ids ) && $img_ids !== [] ) {
			foreach ( $img_ids as $img_id ) {
				$img_url = wp_get_attachment_url( $img_id );
				$opengraph_image->add_image( $img_url );
			}
		}
	}

	/**
	 * Retrieve the primary and if that doesn't exist first term for the brand taxonomy.
	 *
	 * @param string      $schema_brand The taxonomy the site uses for brands.
	 * @param \WC_Product $product      The product we're finding the brand for.
	 *
	 * @return bool|string The brand name or false on failure.
	 */
	private function get_brand_term_name( $schema_brand, $product ) {
		$primary_term = $this->search_primary_term( [ $schema_brand ], $product );
		if ( ! empty( $primary_term ) ) {
			return $primary_term;
		}
		$terms = get_the_terms( get_the_ID(), $schema_brand );
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			$term_values = array_values( $terms );
			$term        = array_shift( $term_values );

			return $term->name;
		}

		return false;
	}

	/**
	 * Adds the other product images to the OpenGraph output.
	 *
	 * @since 1.0
	 */
	public function og_enhancement() {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return;
		}

		$schema_brand = WPSEO_Options::get( 'woo_schema_brand' );
		if ( $schema_brand !== '' ) {
			$brand = $this->get_brand_term_name( $schema_brand, $product );
			if ( ! empty( $brand ) ) {
				echo '<meta property="product:brand" content="' . esc_attr( $brand ) . '"/>' . "\n";
			}
		}

		/**
		 * Filter: wpseo_woocommerce_og_price - Allow developers to prevent the output of the price in the OpenGraph tags.
		 *
		 * @deprecated 12.5.0. Use the {@see 'Yoast\WP\Woocommerce\og_price'} filter instead.
		 *
		 * @api        bool unsigned Defaults to true.
		 */
		$show_price = apply_filters_deprecated(
			'wpseo_woocommerce_og_price',
			[ true ],
			'Yoast WooCommerce 12.5.0',
			'Yoast\WP\Woocommerce\og_price'
		);

		/**
		 * Filter: Yoast\WP\Woocommerce\og_price - Allow developers to prevent the output of the price in the OpenGraph tags.
		 *
		 * @since 12.5.0
		 *
		 * @api   bool unsigned Defaults to true.
		 */
		$show_price = apply_filters( 'Yoast\WP\Woocommerce\og_price', $show_price );

		if ( $show_price === true ) {
			echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '" />' . "\n";
			echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '" />' . "\n";
		}

		if ( $product->is_in_stock() ) {
			echo '<meta property="product:availability" content="in stock" />' . "\n";
		}

		$sku = $product->get_sku();
		if ( ! empty( $sku ) ) {
			echo '<meta property="product:retailer_item_id" content="' . esc_attr( $sku ) . '" />' . "\n";
		}

		/**
		 * Filter: Yoast\WP\Woocommerce\product_condition - Allow developers to prevent or change the output of the product condition in the OpenGraph tags.
		 *
		 * @api string Defaults to 'new'.
		 * @param \WC_Product $product The product we're outputting.
		 */
		$product_condition = apply_filters( 'Yoast\WP\Woocommerce\product_condition', 'new', $product );
		if ( ! empty( $product_condition ) ) {
			echo '<meta property="product:condition" content="' . esc_attr( $product_condition ) . '" />' . "\n";
		}
	}

	/**
	 * Returns the product object when the current page is the product page.
	 *
	 * @since 4.3
	 *
	 * @return WC_Product|null
	 */
	private function get_product() {
		if ( ! is_singular( 'product' ) || ! function_exists( 'wc_get_product' ) ) {
			return null;
		}

		return wc_get_product( get_queried_object_id() );
	}

	/**
	 * Make sure the OpenGraph description is put out.
	 *
	 * @since 1.0
	 *
	 * @param string $desc The current description, will be overwritten if we're on a product page.
	 *
	 * @return string
	 */
	public function og_desc_enhancement( $desc ) {

		if ( is_product_taxonomy() ) {

			$term_desc = term_description();

			if ( ! empty( $term_desc ) ) {
				$desc = wp_strip_all_tags( $term_desc, true );
				$desc = strip_shortcodes( $desc );
			}
		}

		return $desc;
	}

	/**
	 * Return 'product' when current page is, well... a product.
	 *
	 * @since 1.0
	 *
	 * @param string $type Passed on without changing if not a product.
	 *
	 * @return string
	 */
	public function return_type_product( $type ) {
		if ( is_singular( 'product' ) ) {
			return 'product';
		}

		return $type;
	}

	/**
	 * Returns the meta description. Checks which value should be used when the given meta description is empty.
	 *
	 * It will use the short_description if that one is set. Otherwise it will use the full
	 * product description limited to 156 characters. If everything is empty, it will return an empty string.
	 *
	 * @param string $meta_description The meta description to check.
	 *
	 * @return string The meta description.
	 */
	public function metadesc( $meta_description ) {

		if ( $meta_description !== '' ) {
			return $meta_description;
		}

		if ( ! is_singular( 'product' ) ) {
			return '';
		}

		$product = $this->get_product_for_id( get_the_id() );

		if ( ! is_object( $product ) ) {
			return '';
		}

		$short_description = $this->get_short_product_description( $product );
		$long_description  = $this->get_product_description( $product );

		if ( $short_description !== '' ) {
			return $short_description;
		}

		if ( $long_description !== '' ) {
			return wp_html_excerpt( $long_description, 156 );
		}

		return '';
	}

	/**
	 * Checks if product class has a short description method. Otherwise it returns the value of the post_excerpt from
	 * the post attribute.
	 *
	 * @since 4.9
	 *
	 * @param WC_Product $product The product.
	 *
	 * @return string
	 */
	protected function get_short_product_description( $product ) {
		if ( method_exists( $product, 'get_short_description' ) ) {
			return $product->get_short_description();
		}

		return $product->post->post_excerpt;
	}

	/**
	 * Checks if product class has a description method. Otherwise it returns the value of the post_content.
	 *
	 * @since 4.9
	 *
	 * @param WC_Product $product The product.
	 *
	 * @return string
	 */
	protected function get_product_description( $product ) {
		if ( method_exists( $product, 'get_description' ) ) {
			return $product->get_description();
		}

		return $product->post->post_content;
	}

	/**
	 * Checks if product class has a short description method. Otherwise it returns the value of the post_excerpt from
	 * the post attribute.
	 *
	 * @param WC_Product|null $product The product.
	 *
	 * @return string
	 */
	protected function get_product_short_description( $product = null ) {
		if ( is_null( $product ) ) {
			$product = $this->get_product();
		}

		if ( method_exists( $product, 'get_short_description' ) ) {
			return $product->get_short_description();
		}

		return $product->post->post_excerpt;
	}

	/**
	 * Filters the archive link on the product sitemap.
	 *
	 * @param string $link      The archive link.
	 * @param string $post_type The post type to check against.
	 *
	 * @return bool
	 */
	public function xml_post_type_archive_link( $link, $post_type ) {

		if ( $post_type !== 'product' ) {
			return $link;
		}

		if ( function_exists( 'wc_get_page_id' ) ) {
			$shop_page_id = wc_get_page_id( 'shop' );
			$home_page_id = (int) get_option( 'page_on_front' );
			if ( $shop_page_id === -1 || $home_page_id === $shop_page_id ) {
				return false;
			}
		}

		return $link;
	}

	/**
	 * Returns the ID of the WooCommerce shop page when product's archive is requested.
	 *
	 * @param int    $page_id   The page id.
	 * @param string $post_type The post type to check against.
	 *
	 * @return int
	 */
	public function xml_post_type_archive_page_id( $page_id, $post_type ) {

		if ( $post_type === 'product' && function_exists( 'wc_get_page_id' ) ) {
			$page_id = wc_get_page_id( 'shop' );
		}

		return $page_id;
	}

	/**
	 * Initializes the Yoast SEO WooCommerce HelpScout beacon.
	 */
	public function init_beacon() {
		$helpscout = new WPSEO_HelpScout(
			'8535d745-4e80-48b9-b211-087880aa857d',
			[ 'wpseo_woo' ],
			[ WPSEO_Addon_Manager::WOOCOMMERCE_SLUG ]
		);

		$helpscout->register_hooks();
	}

	/**
	 * Checks if the current page is a woocommerce seo plugin page.
	 *
	 * @param string $page Page to check against.
	 *
	 * @return bool
	 */
	protected function is_woocommerce_page( $page ) {
		$woo_pages = [ 'wpseo_woo' ];

		return in_array( $page, $woo_pages, true );
	}

	/**
	 * Enqueues the pluginscripts.
	 */
	public function enqueue_scripts() {
		// Only do this on product pages.
		if ( get_post_type() !== 'product' ) {
			return;
		}

		$asset_manager = new WPSEO_Admin_Asset_Manager();
		$version       = $asset_manager->flatten_version( self::VERSION );

		wp_enqueue_script( 'wp-seo-woo', plugins_url( 'js/dist/yoastseo-woo-plugin-' . $version . '.js', WPSEO_WOO_PLUGIN_FILE ), [], WPSEO_VERSION, true );
		wp_enqueue_script( 'wp-seo-woo-replacevars', plugins_url( 'js/dist/yoastseo-woo-replacevars-' . $version . '.js', WPSEO_WOO_PLUGIN_FILE ), [], WPSEO_VERSION, true );

		wp_localize_script( 'wp-seo-woo', 'wpseoWooL10n', $this->localize_woo_script() );
		wp_localize_script( 'wp-seo-woo-replacevars', 'wpseoWooReplaceVarsL10n', $this->localize_woo_replacevars_script() );
	}

	/**
	 * Registers variable replacements for WooCommerce products.
	 */
	public function register_replacements() {
		wpseo_register_var_replacement(
			'wc_price',
			[ $this, 'get_product_var_price' ],
			'basic',
			'The product\'s price.'
		);

		wpseo_register_var_replacement(
			'wc_sku',
			[ $this, 'get_product_var_sku' ],
			'basic',
			'The product\'s SKU.'
		);

		wpseo_register_var_replacement(
			'wc_shortdesc',
			[ $this, 'get_product_var_short_description' ],
			'basic',
			'The product\'s short description.'
		);

		wpseo_register_var_replacement(
			'wc_brand',
			[ $this, 'get_product_var_brand' ],
			'basic',
			'The product\'s brand.'
		);
	}

	/**
	 * Register the promotion class for our GlotPress instance.
	 *
	 * @link https://github.com/Yoast/i18n-module
	 */
	protected function register_i18n_promo_class() {
		new Yoast_I18n_v3(
			[
				'textdomain'     => 'yoast-woo-seo',
				'project_slug'   => 'woocommerce-seo',
				'plugin_name'    => 'Yoast WooCommerce SEO',
				'hook'           => 'wpseo_admin_promo_footer',
				'glotpress_url'  => 'http://translate.yoast.com/gp/',
				'glotpress_name' => 'Yoast Translate',
				'glotpress_logo' => 'http://translate.yoast.com/gp-templates/images/Yoast_Translate.svg',
				'register_url'   => 'http://translate.yoast.com/gp/projects#utm_source=plugin&utm_medium=promo-box&utm_campaign=wpseo-woo-i18n-promo',
			]
		);
	}

	/**
	 * Returns the set image ids for the given product.
	 *
	 * @since 4.9
	 *
	 * @param WC_Product $product The product to get the image ids for.
	 *
	 * @return array
	 */
	protected function get_image_ids( $product ) {
		if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
			return $product->get_gallery_image_ids();
		}

		// Backwards compatibility.
		return $product->get_gallery_attachment_ids();
	}

	/**
	 * Returns the product for given product_id.
	 *
	 * @since 4.9
	 *
	 * @param int $product_id The id to get the product for.
	 *
	 * @return WC_Product|null
	 */
	protected function get_product_for_id( $product_id ) {
		if ( function_exists( 'wc_get_product' ) ) {
			return wc_get_product( $product_id );
		}

		if ( function_exists( 'get_product' ) ) {
			return get_product( $product_id );
		}

		return null;
	}

	/**
	 * Retrieves the product price.
	 *
	 * @since 5.9
	 *
	 * @return string
	 */
	public function get_product_var_price() {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return '';
		}

		if ( method_exists( $product, 'get_price' ) ) {
			return wp_strip_all_tags( wc_price( $product->get_price() ), true );
		}

		return '';
	}

	/**
	 * Retrieves the product short description.
	 *
	 * @since 5.9
	 *
	 * @return string
	 */
	public function get_product_var_short_description() {
		return $this->get_product_short_description();
	}

	/**
	 * Retrieves the product SKU.
	 *
	 * @since 5.9
	 *
	 * @return string
	 */
	public function get_product_var_sku() {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return '';
		}

		if ( method_exists( $product, 'get_sku' ) ) {
			return $product->get_sku();
		}

		return '';
	}

	/**
	 * Retrieves the product brand.
	 *
	 * @since 5.9
	 *
	 * @return string
	 */
	public function get_product_var_brand() {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return '';
		}

		$brand_taxonomies = [
			'product_brand',
			'pwb-brand',
		];

		$brand_taxonomies = array_filter( $brand_taxonomies, 'taxonomy_exists' );

		$primary_term = $this->search_primary_term( $brand_taxonomies, $product );
		if ( $primary_term !== '' ) {
			return $primary_term;
		}

		foreach ( $brand_taxonomies as $taxonomy ) {
			$terms = get_the_terms( $product->get_id(), $taxonomy );
			if ( is_array( $terms ) ) {
				return $terms[0]->name;
			}
		}

		return '';
	}

	/**
	 * Searches for the primary terms for given taxonomies and returns the first found primary term.
	 *
	 * @param array      $brand_taxonomies The taxonomies to find the primary term for.
	 * @param WC_Product $product          The WooCommerce Product.
	 *
	 * @return string The term's name (if found). Otherwise an empty string.
	 */
	protected function search_primary_term( array $brand_taxonomies, $product ) {
		// First find the primary term.
		if ( ! class_exists( 'WPSEO_Primary_Term' ) ) {
			return '';
		}

		foreach ( $brand_taxonomies as $taxonomy ) {
			$primary_term       = new WPSEO_Primary_Term( $taxonomy, $product->get_id() );
			$found_primary_term = $primary_term->get_primary_term();

			if ( $found_primary_term ) {
				$term = get_term_by( 'id', $found_primary_term, $taxonomy );

				return $term->name;
			}
		}

		return '';
	}

	/**
	 * Localizes scripts for the WooCommerce Replacevars plugin.
	 *
	 * @return array The localized values.
	 */
	protected function localize_woo_replacevars_script() {
		return [
			'currency'       => get_woocommerce_currency(),
			'currencySymbol' => get_woocommerce_currency_symbol(),
			'decimals'       => wc_get_price_decimals(),
			'locale'         => str_replace( '_', '-', get_locale() ),
		];
	}

	/**
	 * Localizes scripts for the wooplugin.
	 *
	 * @return array
	 */
	private function localize_woo_script() {
		$asset_manager = new WPSEO_Admin_Asset_Manager();
		$version       = $asset_manager->flatten_version( self::VERSION );

		return [
			'script_url'     => plugins_url( 'js/dist/yoastseo-woo-worker-' . $version . '.js', self::get_plugin_file() ),
			'woo_desc_none'  => __( 'You should write a short description for this product.', 'yoast-woo-seo' ),
			'woo_desc_short' => __( 'The short description for this product is too short.', 'yoast-woo-seo' ),
			'woo_desc_good'  => __( 'Your short description has a good length.', 'yoast-woo-seo' ),
			'woo_desc_long'  => __( 'The short description for this product is too long.', 'yoast-woo-seo' ),
		];
	}

	/**
	 * Handles the WooCommerce breadcrumbs replacements.
	 *
	 * @return void
	 */
	public function handle_breadcrumbs_replacements() {
		if ( WPSEO_Options::get( 'woo_breadcrumbs' ) !== true || WPSEO_Options::get( 'breadcrumbs-enable' ) !== true ) {
			return;
		}

		if ( has_action( 'storefront_before_content', 'woocommerce_breadcrumb' ) ) {
			remove_action( 'storefront_before_content', 'woocommerce_breadcrumb' );
			add_action( 'storefront_before_content', [ $this, 'show_yoast_breadcrumbs' ] );
		}

		// Replaces the WooCommerce breadcrumbs.
		if ( has_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
			add_action( 'woocommerce_before_main_content', [ $this, 'show_yoast_breadcrumbs' ], 20, 0 );
		}

		add_filter( 'wpseo_breadcrumb_links', [ $this, 'add_attribute_to_breadcrumbs' ] );
	}

	/**
	 * Refresh the options property on add/update of the option to ensure it's always current.
	 *
	 * @deprecated 12.5
	 * @codeCoverageIgnore
	 */
	public function refresh_options_property() {
		_deprecated_function( __METHOD__, 'WPSEO Woo 12.5' );
	}

	/**
	 * Perform upgrade procedures to the settings.
	 *
	 * @deprecated 12.5
	 * @codeCoverageIgnore
	 */
	public function upgrade() {
		_deprecated_function( __METHOD__, 'WPSEO Woo 12.5' );
	}

	/**
	 * Simple helper function to show a checkbox.
	 *
	 * @deprecated 12.5
	 * @codeCoverageIgnore
	 */
	public function checkbox() {
		_deprecated_function( __METHOD__, 'WPSEO Woo 12.5' );
	}
}
