<?php

/**
 * Class Es_Dashboard_Page
 */
class Es_Dashboard_Page extends Es_Object
{
    /**
     * Add actions for dashboard page.
     */
    public function actions()
    {
        add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_styles' ) );
    }

    /**
     * Enqueue styles for dashboard page.
     *
     * @return void
     */
    public function enqueue_styles()
    {
        $vendor = 'admin/assets/css/vendor/';
        $main_vendor = 'assets/css/vendor/';

        wp_register_style( 'es-scroll-style', ES_PLUGIN_URL . $vendor . 'jquery.mCustomScrollbar.css' );
        wp_enqueue_style( 'es-scroll-style' );

        wp_register_style( 'es-admin-slick-style', ES_PLUGIN_URL . $main_vendor . 'slick.min.css' );
        wp_register_style( 'es-admin-slick-theme-style', ES_PLUGIN_URL . $main_vendor . 'slick-theme.min.css' );

        wp_enqueue_style( 'es-admin-slick-style' );
        wp_enqueue_style( 'es-admin-slick-theme-style' );
    }

    /**
     * Render dashboard page.
     *
     * @return void
     */
    public static function render()
    {
        $template = apply_filters( 'es_dashboard_page_template_path', ES_ADMIN_TEMPLATES . '/dashboard/dashboard.php' );

        if ( file_exists( $template ) ) {
            include_once( $template );
        }
    }

    /**
     * Return shortcodes list for dashboard page.
     *
     * @return array
     */
    public static function get_shortcodes_list()
    {
        return apply_filters( 'es_get_shortcodes_list', array(
            '[es_my_listing layout="list | 3_col | 2_col"]',
            '[es_my_listing sort="recent | highest_price | lowest_price | most_popular | oldest"]',
            '[es_my_listing  prop_id="1,2,5,6...n"]',
            '[es_my_listing category="category name"]',
            '[es_my_listing status="status name"]',
            '[es_my_listing type="type name"]',
            '[es_my_listing rent_period="rent period 1"]',
            '[es_property_map show="all"] (PRO)',
            '[es_property_map type="your type"] (PRO)',
            '[es_property_map category="your category"] (PRO)',
            '[es_property_map status="your status"] (PRO)',
            '[es_property_map rent_period="your period"] (PRO)',
            '[es_property_map limit=20] (PRO)',
            '[es_property_map ids="1,2,3,4,5"] (PRO)',
            '[es_property_map address="your address string"] (PRO)',
            '[es_property_slideshow] (PRO)',
            '[es_city city="city name"] (PRO)',
            '[es_state state="state name"] (PRO)',
            '[es_country country="country name"] (PRO)',
            '[es_labels label="label_name"] (PRO)',
            '[es_features feature="a,b,c,d"] (PRO)',
            '[es_featured_props] ',
            '[es_latest_props] ',
            '[es_cheapest_props]',
            '[es_agents] (PRO)',
            '[es_listing_agent name="agent username"] (PRO)',
            '[es_subscription_table] (PRO)',
            '[es_register] (PRO)',
            '[es_login] (PRO)',
            '[es_prop_management] (PRO)',
            '[es_reset_pwd] (PRO)',
        ) );
    }

    /**
     * Return changelog array for dashboard page.
     *
     * @return mixed
     */
    public static function get_changelog_list()
    {
        return apply_filters( 'es_get_changelog_list', array(

			__( '3.11.4 (September, 13, 2021)', 'es-plugin' ) => '<ul>
				<li>NEW: Lightbox On/Off option to Settings added (Elementor builder conflict fixed) (all versions)</li>
				<li>NEW: Single property photo gallery shortcode added [es_property_gallery property_id=\'property ID\'] (all versions)</li>
				<li>NEW: Support for comma-separated values to address type fields added (all versions)</li>
				<li>NEW: Property management template override option in child theme added</li>
				<li>NEW: Optimized the sql query for properties map (PRO & Premium)</li>
				<li>FIX: Agent\'s name display issue in Saved Search fixed (PRO & Premium)</li>
				<li>FIX: Favorite listings fixed in My profile (PRO & Premium)</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.11.3 (August, 6, 2021)', 'es-plugin' ) => '<ul>
				<li>FIX: The order of search fields issue fixed (all versions)</li>
				<li>FIX: Deleted Field range mode buttons in Fields Builder (all versions)</li>
				<li>FIX: Profile conflict with Divi theme fixed (PRO & Premium)</li>
				<li>FIX: The field that should be visible only for admin fixed (PRO & Premium)</li>
				<li>FIX: Property box bug settings fixed (PRO&Premium)</li>
				<li>FIX: Added an error handler for MLS Connect action (PREMIUM)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.11.2 (July, 16, 2021)', 'es-plugin' ) => '<ul>
                <li>FIX: Deletion currencies in Data Manager fixed (all versions)</li>
                <li>FIX: Icons in a listing box fixed (all versions)</li>
                <li>FIX: Removed "Neighborhood" tab when "Hide map on Single Page" button is on (all versions) </li>
                <li>FIX: Empty map section in single property page fixed (all versions)</li>
                <li>FIX: CSV Import issue fixed (PRO & Premium)</li>
                <li>FIX: Save search issue fixed (PRO & Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.11.1 (June, 17, 2021)', 'es-plugin' ) => '<ul>
                <li>FIX: Display of address components via es_property_field shortcode fixed (all versions)</li>
                <li>FIX: Login form fields markup fixed  (all versions)</li>
                <li>FIX: Beds, baths translation strings fixed  (all versions)</li>
                <li>FIX: Video tab bug fixed  (all versions)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.11.0 (June, 4, 2021)', 'es-plugin' ) => '<ul>
                <li>NEW: Pre-defined fields option in search form shortcode added (all versions)</li>
                <li>NEW: Login via social media Google/Facebook added (all versions)</li>
                <li>NEW: Label custom colors added (all versions)</li>
                <li>NEW: Option to display custom fields & icons in property boxes added (PRO & Premium)</li>
                <li>NEW: CSV import format modified (images separation by comma added) (PRO & Premium)</li>
                <li>NEW: HTML field type in FB added (all versions)</li>
                <li>FIX: Link "Set filter fields" in MLS Import fixed (Premium)</li>
                <li>FIX: !important from title style of any widget removed (all versions)</li>
                <li>FIX: "Saved searches" feature fixed (PRO & Premium) </li>
                <li>FIX: Excluded es_get_default_thumbnail_id function from plugin (all versions)</li>
                <li>FIX: Search shortcode issue fixed (all versions)</li>
                <li>FIX: Estatik Search widget fields order in Elementor fixed (all versions)</li>
                <li>FIX: Maps issue with address components for UK, Spain fixed (all versions)</li>
                <li>FIX: Select2 selected options width fixed (all versions)</li>
                <li>FIX: Default fields deletion process from front-end management fixed (PRO & Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.10.4 (April, 26, 2021)', 'es-plugin' ) => '<ul>
                <li>NEW: Option to disable automatic deletion of images when deleting listings (all versions)</li>
                <li>NEW: Option "Show labels" in Slideshow widget and shortcode added (all versions)</li>
                <li>NEW: Option "Disable image url validation" to check validity of images URLs from MLS added (Premium)</li>
                <li>NEW: Integration with Spark API for images import added (Premium)</li>
                <li>NEW: Auto-suggest option to Synchronization added (Premium)</li>
                <li>FIX: Registration form issue fixed (all versions)</li>
                <li>FIX: French and Dutch translation issue fixed (all versions)</li>
                <li>FIX: PDF issue fixed (PRO & Premium versions)</li>
                <li>FIX: Agent issue in My Profile fixed (PRO & Premium versions)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.10.3 (April, 1, 2021)', 'es-plugin' ) => '<ul>
                <li>NEW: Disable map option in PDF settings added (PRO & Premium)</li>
                <li>NEW: Bridge MLS media support added (Premium)</li>
                <li>FIX: Equal/Not equal operators issue fixed (Premium)</li>
                <li>FIX: Listing editing in My Profile fixed (PRO & Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.10.2 (March, 25, 2021)', 'es-plugin' ) => '<ul>
                <li>NEW: Search page id parameter in the Elementor search widget added (all versions)</li>
                <li>NEW: Shortcode to show specific listings by a title added (all version)</li>
                <li>NEW: URL to Support Area in Estatik Dashboard added (all versions)</li>
                <li>NEW: “Equal/Not equal” added in MLS schedule (Premium)</li>
                <li>NEW: Shortcode to display listings for specific MLS accounts added (Premium)</li>
                <li>NEW: Queue Limit Field in Synchronization added (Premium)</li>
                <li>NEW: Subscription duration in Paypal Subscription added (PRO & Premium)</li>
                <li>FIX: Description issue fixed (all versions)</li>
                <li>FIX: Shortcode for displaying listings with labels fixed (all version)</li>
                <li>FIX: Single property shortcode render for Avada theme & Fusion Page Builder fixed (all version)</li>
                <li>FIX: Estatik Slideshow widget issue fixed (all version)</li>
                <li>FIX: CREA DDF synchronizer issue fixed (Premium)</li>
                <li>FIX: Profile name before flash messages added (Premium)</li>
                <li>FIX: Address issue when map icon moved fixed (PRO & Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.10.1 (February, 25, 2021)', 'es-plugin' ) => '<ul>
                <li>FIX: Description box editor bug fixed (All versions)</li>
                <li>FIX: Taxonomy terms duplications on property edit page fixed (PREMIUM)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.10.0 (February, 19, 2021)', 'es-plugin' ) => '<ul>
                <li>Elementor Page Builder and Theme support added (all versions). <a target="_blank" href="https://estatik.net/estatik-elementor-compatible-release-ready/">Learn more</a></li>
                <li>NEW: Filter \'Exclude fields\' to Manual/Automatic import added (Premium)</li>
                <li>NEW: Option "Skip properties without images" in Settings added (Premium)</li>
                <li>FIX: Images import fix added (Premium)</li>
                <li>FIX: Bulleted list in custom fields fixed (all versions)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.9.10 (January, 22, 2021)', 'es-plugin' ) => '<ul>
                <li>NEW: Box-sizing issue fixed (border-box to plugin buttons es-btn added) (all versions)</li>
                <li>NEW: Year built, post added, post updated fields to the search widget added (all versions)</li>
                <li>FIX: Select2 dropdown position fixed (all versions)</li>
                <li>FIX: Duplicated listings after search fixed (all versions)</li>
                <li>FIX: Executed column Reset issue fixed (Premium) </li>
                <li>FIX: Request form email headers fixed (all versions)</li>
                <li>FIX: Custom fields removal from Search issue fixed (all versions)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.9.9 (December, 23, 2020)', 'es-plugin' ) => '<ul>
                <li>NEW: Asterisk to required fields added (all versions)</li>
		        <li>NEW: Option to switch on/off email notifications when property added by agent added (PRO & Premium)</li>
		        <li>NEW: Filter es_mls_get_entity_importer to adjust import of listings without images (Premium)</li>
		        <li>NEW: Extra field for Google Map API key with server IP restriction added (Premium)</li>
		        <li>FIX: Warning about empty required field fixed (all versions)</li>
		        <li>FIX: Data manager issue fixed (all versions)</li>
		        <li>FIX: Issue with saving values in search widget select2 fields fixed (all versions)</li>
		        <li>FIX: Plugin profile layout issue on "Twenty Twenty" theme fixed (all versions)</li>
		        <li>FIX: List/grid layout in General settings fixed (all versions)</li>
		        <li>FIX: Close button in map popup window fixed (PRO & Premium)</li>
		        <li>FIX: Filterable field checkbox issue in Fields Configurations fixed (Premium)</li>
		        <li>FIX: Queue generation issue fixed (Premium)</li>
		        <li>FIX: Duplicate error notifications fixed (Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.9.8 (October, 28, 2020)', 'es-plugin' ) => '<ul>
		        <li>NEW: Added the ability to override the media.php template (All versions)</li>
		        <li>NEW: Added the option "Hide map >> yes/ no" in the "General Settings" (All versions)</li>
		        <li>NEW: Added province field for Spanish addresses. (All versions)</li>
		        <li>NEW: Added Details Popup to Automatic Import under Executed items (Premium)</li>
		        <li>FIX: The "Show filter" issue in the Estatik shortcode builder fixed (all version)</li>
		        <li>FIX: Fixed jquery hash link warning (all versions)</li>
		        <li>FIX: Fixed slideshow responsive for mobile devices (PRO & Premium)</li>
		        <li>FIX: Map Cluster icon 404 error fixed (PRO & Premium)</li>
		        <li>FIX: Сreation of listing in my profile only from the agent is fixed (PRO & Premium)</li>
		        <li>FIX: “Nothing to import” issue in manual import fixed (Premium)</li>
		        <li>FIX: Deleted actualizer delete entities unused action. (Premium)</li>
		        <li>FIX: Fixed PHRETS XML parser. (Premium)</li>
		        <li>FIX: Actualizer query limit upgraded to 2000 items. (Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.9.7 (September, 10, 2020)', 'es-plugin' ) => '<ul>
				<li>NEW: WordPress 5.5.1 support added (all versions)</li>
				<li>NEW: option to override plugin templates via child theme added (all versions)</li>
				<li>NEW: warning popup message added when deleting RETS profile (Premium)</li>
				<li>NEW: map marker popup for multiple listings (units) with same lat & long implemented (PRO & Premium)</li>
				<li>NEW: Web API support added (Premium)</li>
				<li>NEW: updates queue details & reset options in Synchronization added (Premium)</li>
				<li>NEW: extra import tools added (pause, editing, one-time import) (Premium)</li>
				<li>NEW: Debug mode added (Premium)</li>
				<li>NEW: date selection for updates check added (Premium)</li>
				<li>NEW: extra filters for synchronization added (Premium)</li>
				<li>FIX: wp_mail FROM parameter for Request Form widget fixed (all versions)</li>
				<li>FIX: wp_make_content_images_responsive fix added (all versions)</li>
				<li>FIX: delete icon display glitch in Data Manager - Labels - Featured fixed (all versions)</li>
				<li>FIX: tags generation in description fixed (all versions)</li>
				<li>FIX: PDF flyer bug for PHP 7 fixed (PRO & Premium)</li>
				<li>FIX: CSS class for html body in Agents (admin area) fixed (PRO & Premium)</li>
				<li>FIX: address skipping in RETS synchronizer fixed (Premium)</li>
				<li>FIX: MLS properties cache issue fixed (Premium)</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.9.6 (June, 6, 2020)', 'es-plugin' ) => '<ul>
				<li>NEW: REST support on/off option added (all versions)</li>
				<li>FIX: tmp phrets files creation fixed (Premium)</li>
				<li>FIX: Paddings in Accesspress tabs fixed (all versions)</li>
				<li>FIX: Tags issue fixed (PRO & Premium)</li>
				<li>FIX: MLS date field fixed (PRO & Premium)</li>
				<li>FIX: Google address geocoding request URL fixed (PRO & Premium)</li>
				<li>FIX: Estatik Search widget issue fixed (All versions)</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.9.5 (May, 5, 2020)', 'es-plugin' ) => '<ul>
				<li>NEW: Auto tags functionality implemented (all versions)</li>
				<li>NEW: Dynamic content feature added (all versions)</li>
				<li>NEW: Google maps popups improved (PRO & Premium)</li>
				<li>FIX: View first filter fixed "Most popular" changed to "Featured" (all versions)</li>
				<li>FIX: MLS date field fixed (PRO & Premium)</li>
				<li>FIX: Dropdown lists in Shortcode Builder fixed (all versions)</li>
				<li>FIX: Footer on the first page of the pdf file fixed (PRO & Premium)</li>
				<li>FIX: "Combine CSS File" is not broken layout in the SG Optimizer</li>
				<li>FIX: "Choose value" in the search form fields fixed  (all versions)</li>
				<li>FIX: "Estatik Search" widget issue fixed  (all versions)</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.9.4 (February, 5, 2020)', 'es-plugin' ) => '<ul>
				<li>NEW: FB Select Type field values auto-import implemented (Premium)</li>
				<li>NEW: Yes/No Disable sticky property top bar option added (all versions)</li>
				<li>NEW: New attributes for es_my_listing shortcode added: bathrooms, bedrooms, bathrooms_min, bathrooms_max, bedrooms_min, bedrooms_max, lot_size_min, lot_size_max, area_min, area_max, price_min, price_max, area, lot_size (all versions)</li>
				<li>UPDATE: Upgraded plugin update library to 4.9 ver. (PRO & Premium)</li>
				<li>FIX: Disable editor auto paragraph issue fixed (all versions)</li>
				<li>FIX: Editor auto paragraph disabled by default (all versions)</li>
				<li>FIX: PDF issue fixed</li>
				<li>FIX: PDF Single Photo layout fixed</li>
				<li>FIX: Agent username issue fixed</li>
				<li>FIX: reCAPTCHA issue fixed</li>
				<li>FIX: Map markers issue fixed</li>
				<li>FIX: RETS images import with no file extension fixed (Premium)</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.9.3 (February, 5, 2020)', 'es-plugin' ) => '<ul>
				<li>NEW: Shortcode [es_my_listing sort="oldest"] (all versions)</li>
				<li>NEW: Area shortcodes (all versions) </li>
				<li>NEW: PDF settings (excerpt display added)</li>
				<li>NEW: Added wp filter for default dmql2 query (Premium)</li>
				<li>FIX: Request form bugs fixed (all versions)</li>
				<li>FIX: Conflicts with some other plugins fixed (all versions)</li>
				<li>FIX: PDF layout & language issues fixed (all versions)</li>
				<li>FIX: Duplicated parsed query for admin listings page removed (all versions)</li>
				<li>FIX: Fixed errors in language files (all versions)</li>
				<li>FIX: Login popup issue fixed (all versions)</li>
				<li>FIX: Problem with Quotes\Slashes in Fields Builder Select Field & Property Search fixed (all versions)</li>
				<li>FIX: Fixed subscriptions table access permissions (PRO & Premium)</li>
				<li>FIX: New field name issue fixed (Premium)</li>
				<li>FIX: Invalid RETS Version check added (Premium)</li>
				<li>FIX: Field type check added (PRO)</li>
				<li>FIX: Fixed agent rating display</li>
				<li>FIX: Fixed slideshow render with empty properties images</li>
				<li>FIX: Properties sorting markup fixed</li>
				<li>FIX: Added new permissions for admin pages</li>
				<li>FIX: Fixed RETS fields association</li>
				<li>FIX: esc_url function added for FB URL type field</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.9.2 (November, 11, 2019)', 'es-plugin' ) => '<ul>
				<li>Request form sending issue fixed</li>
				<li>Duplicated Terms of use checkbox texts removed </li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.9.1 (November, 1, 2019)', 'es-plugin' ) => '<ul>
				<li>NEW: Fields reorder feature to frontend management added (PRO & Premium)</li>
				<li>NEW: Agents rating on/off option added  (PRO)</li>
				<li>NEW: German language files edited</li>
				<li>NEW: Labels Show/hide option on single property page added  (PRO & Premium)</li>
				<li>FIX: Inode usage - phrets files deletion from server (Premium)</li>
				<li>FIX: Post SMTP compatibility fixed</li>
				<li>FIX: Wishlist items removal fixed</li>
				<li>FIX: Bullet list issue fixed</li>
				<li>FIX: Texts in MLS RETS Import fixed</li>
				<li>FIX: Section order issue in Field Builder fixed</li>
				<li>FIX: Notifications display in admin area fixed</li>
				<li>FIX: html editor issues (list bullets) in description property field fixed</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.9.0 (August, 10, 2019)', 'es-plugin' ) => '<ul>
				<li>reCaptcha v3 support added</li>
				<li>Admin settings tabs layout improved</li>
				<li>New shortcode [es_my_listing_rets_class="name"] added (Premium)</li>
				<li>slick slider script updated</li>
				<li>Listings removal when agent is deleted issue fixed</li>
				<li>Call for price query fixed (Premium)</li>
				<li>Frontend management pagination issue fixed (PRO & Premium)</li>
				<li>Request form widget bugs fixed</li>
				<li>new shortcode [es_cron_last_executed_date] added (Premium)</li>
				<li>Empty fields in saved searches display fixed</li>
				<li>minor fixes</li>
			</ul>',

            __( '3.8.9 (July, 29, 2019)', 'es-plugin' ) => '<ul>
				<li>FB fields & sections saving bug fixed</li>
				<li>Added locate functions for Slideshow widget</li>
				<li>Added filter for locate template function</li>
				<li>filter names for admin pages urls fixed</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.8.8 (July, 10, 2019)', 'es-plugin' ) => '<ul>
				<li>NEW: Custom email field to Request widget added (all versions)</li>
				<li>NEW: Drag & drop Google Maps marker in admin area added (all versions)</li>
				<li>NEW: new Estatik Realtor Theme support added (all versions)</li>
				<li>NEW: Social fields and icons added to agent profile (PRO & Premium)</li>
				<li>NEW: Property title & image became clickable on map popup windows (PRO & Premium)</li>
				<li>NEW: Removal option for extra area dimensions in search widget added (all versions)</li>
				<li>FIX: Search widget taxonomy translation function added (all versions)</li>
				<li>FIX: Field sections order issue fixed (all versions)</li>
				<li>FIX: Duplicated db queries fixed (all versions)</li>
				<li>FIX: Text shift issue on full-width single property layout fixed (all versions)</li>
				<li>FIX: Padding issue on agent profile fixed (PRO & Premium)</li>
				<li>FIX: Text shift issue on listings responsive layouts fixed (all versions)</li>
				<li>FIX: Placeholder color issue in search widget fixed (all versions)</li>
				<li>FIX: Request form sending without message entered issue fixed (all versions)</li>
				<li>FIX: Pagination issue in My profile fixed (PRO & Premium)</li>
				<li>FIX: Custom Date type field frontend format fixed (PRO & Premium)</li>
				<li>FIX: Map popup markup fixed (PRO & Premium)</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.8.7.1 (May, 16, 2019)', 'es-plugin' ) => '<ul>
				<li>area & lot size dimensions issue fixed</li>
			</ul>',

	        __( '3.8.7 (May, 16, 2019)', 'es-plugin' ) => '<ul>
				<li>Sections layout fixed in FB</li>
				<li>Demo listings import fixed</li>
				<li>Agent pwd reset procedure fixed (PRO &Premium)</li>
				<li>Front-end management tabs fixed (PRO &Premium)</li>
				<li>New limit parameter added to shortcode [es_my_listing] (PRO &Premium)</li>
				<li>Map popup markup fixed</li>
				<li>minor fixes</li>
			</ul>',

	        __( '3.8.6 (April, 23, 2019)', 'es-plugin' ) => '<ul>
				<li>Slideshow widget bugs fixed</li>
				<li>Hiding for default fields in FB fixed</li>
				<li>Fixed responsive layout for listings list.</li>
				<li>Taxonomies in frontend submission fixed</li>
				<li>Adaptive height of images in single property gallery fixed</li>
				<li>Property management form with required fields submit fixed</li>
			</ul>',

	        __( '3.8.5 (April, 08, 2019)', 'es-plugin' ) => '<ul>
				<li>Price range parameter to Slideshow widget added</li>
				<li>Map markers zoom issue fixed</li>
				<li>PHP 7.2 compatibility fixed</li>
				<li>Sorting on search results pages fixed</li>
				<li>Search widget conflict with Elementor builder solved</li>
				<li>Security fixes</li>
				<li>Some optimization fixes</li>
				<li>PDF flyer feature upgraded (PRO & Premium)</li>
				<li>Mulitple fields drag & drop feature added (PRO & Premium)</li>
				<li>Option to assign multilple listings to Agent added (PRO & Premium)</li>
				<li>Reset option for Updated/Deleted listings added (Premium)</li>
				<li>Updated listings logs in a popup added (Premium)</li>
				<li>Total # of listings in Manual import added (Premium)</li>
				<li>Filter by price range added (Premium)</li>
				<li>Fixed property empty fields saving</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.8.4 (March, 20, 2019)', 'es-plugin' ) => '<ul>
			    <li>Security fixes</li>
			    <li>Removed templates for estatik taxonomies. Created taxonomy.php as base template for each estatik taxonomy.</li>
			    <li>Fixed Single property gallery dimensions.</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.8.3 (March, 05, 2019)', 'es-plugin' ) => '<ul>
			    <li>Added price range selection to Slideshow widget & shortcode</li>
			    <li>Added custom field parameter to [es_my_listing] shortcode</li>
			    <li>To top link fixed</li>
			    <li>Map widget height parameter fixed (PRO)</li>
			    <li>Custom field in PDF brochure display fixed (PRO)</li>
			    <li>Min - max price parameters in search fixed</li>
			    <li>Agent profile image upload fixed (PRO)</li>
			    <li>Sharing feature with Gutenberg conflict fixed</li>
			    <li>Added agent id csv import support (PRO)</li>
			    <li>Subscriptions Fix (PRO)</li>
			    <li>Sort by price optimized</li>
			    <li>wpautop configured for properties post type.</li>
			    <li>Fixed property archive item markup</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.8.2 (January, 16, 2019)', 'es-plugin' ) => '<ul>
			    <li>Private field/section added (available for Admin & Agents only)</li>
			    <li>Address shortcodes fixed</li>
			    <li>Estatik global js var localized for plugin js files</li>
			    <li>Pagination GET argument fixed</li>
			    <li>Fixed [es_single] shortcode execute condition for single property page</li>
			    <li>Company attribute added to es_agents shortcode (PRO & Premium)</li>
			    <li>Images upload via frontend issue fixed (PRO & Premium)</li>
			    <li>Fields Configuration UI modified (Premium)</li>
			    <li>Disclaimer saving fixed (Premium)</li>
			    <li>Loopback fix</li>
			    <li>Added dompdf encoding</li>
			    <li>Reset pwd link fixed</li>
			    <li>Price format bug fixed</li>
			    <li>minor fixes</li>
			</ul>',

			__( '3.8.1 (November, 09, 2018)', 'es-plugin' ) => '<ul>
			    <li>Fixed Google Maps marker on single property page</li>
			    <li>Buyer login fixed</li>
			    <li>Shortcode Builder with Elementor builder integrated</li>
			    <li>Request form bug fixed</li>
			    <li>Shortcode Builder icon from frontend management removed (PRO & Premium)</li>
			    <li>Contact agent tab fixed (PRO & Premium)</li>
			    <li>Agent image import fixed (Premium)</li>
			    <li>Added ability to import agent images via media resource (Premium)</li>
			    <li>Added new hooks for address components classes</li>
			    <li>Fixed google maps markers that uses labels taxonomy</li>
			    <li>Fixed wishlist based on cookies (uses for unauthorized users)</li>
			    <li>Added sanitize functions</li>
			    <li>is_post_type_archive() func call fix</li>
			    <li>Login redirects fixed</li>
			    <li>minor fixes</li>
			</ul>',

        	__( '3.8.0 (November, 01, 2018)', 'es-plugin' ) => '<ul>
			    <li>Shortcode builder added</li>
			    <li>Map clusters feature added (PRO & Premium)</li>
			    <li>Bug with Select multiselect type field fixed (PRO & Premium)</li>
			    <li>Bug with Buyers removal when White label enabled fixed (PRO & Premium)</li>
			    <li>Bug in Saved search Price field fixed (PRO & Premium)</li>
			    <li>Excerpt Field in frontend management added (PRO & Premium)</li>
			    <li>Contact agent tab bug fixed (PRO & Premium)</li>
			    <li>Google geocoding function fixed</li>
			    <li>Subscription table fixed (PRO)</li>
			    <li>White label redirection fixed</li>
			    <li>MLS # field fixed (Premium)</li>
			    <li>Disclaimer settings added (Premium)</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.7.2 (October, 03, 2018)', 'es-plugin' ) => '<ul>
			    <li>Excluded Google maps geolocation library</li>
			    <li>Address components DM fix</li>
			    <li>Added single page class instance as global variable</li>
			    <li>Property Image counter notice fix</li>
			    <li>Enabled wishlist feature by default</li>
			    <li>Fixed widget display type notice</li>
			    <li>User login fix</li>
			    <li>Fixed main theme color for plugin buttons</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.7.1 (September, 14, 2018)', 'es-plugin' ) => '<ul>
			    <li>Bug with featured image fixed</li>
			    <li>Option to disable wishlist added</li>
			    <li>Search fields selected option error fixed</li>
			    <li>Plugin color settings fixed</li>
			    <li>Saved search feature saving process fix added</li>
			    <li>minor fixes</li>
			</ul>',

	        __( '3.7.0 (September, 7, 2018)', 'es-plugin' ) => '<ul>
			    <li>NEW: Wishlist added</li>
			    <li>NEW: Saved searches added</li>
			    <li>NEW: User profiles added</li>
			    <li>NEW: Ligthbox for main image added</li>
			    <li>NEW: Slideshow widget added & shortcode added</li>
			    <li>NEW: Fields Builder competely unlocked</li>
			    <li>NEW: Labels editing in Data Manager added</li>
			    <li>NEW: Remove option for featured image added</li>
			    <li>NEW: Agents profiles added (PRO & Premium)</li>
			    <li>NEW: Emails notifications about matching searches added (PRO & Premium)</li>
			    <li>Fix: Multiselect feature for Select type in FB added</li>
			    <li>Fix: Integrated WP HTTP Api instead of file_get_contents function</li>
			    <li>Fix: Address import fix (Premium)</li>
			    <li>minor fixes</li>
			</ul>'

        ,__( '3.6.6 (August, 14, 2018)', 'es-plugin' ) => '<ul>
			    <li>Images q-ty to listings added (admin area)</li>
			    <li>Bug with File type in FB fixed</li>
			    <li>Save Settings bug fixed</li>
			    <li>Multiselect feature for ShelterZoom category field added (PRO & Premium)</li>
			    <li>Fixed image size for fb sharing</li>
			    <li>Images import via URLs (for Matrix system) (Premium)</li>
			    <li>Filter by MLS # in admin panel added (Premium)</li>
			    <li>Added Deleted value column in Synchronization panel (Premium)</li>

			    <li>minor fixes</li>
			</ul>',

	        __( '3.6.5 (July, 6, 2018)', 'es-plugin' ) => '<ul>
                <li>Fields Builder perfomance optimized</li>
                <li>Empty search results for shortcodes with es_city, es_country & es_my_listings address fixed</li>
                <li>Fixed render of FB fields with the same names</li>
                <li>Option to remove Cities in Data Manager added</li>
                <li>Custom Date type field bug fixed</li>
                <li>Price format fixes</li>
                <li>Synchronizator fixes (Premium)</li>
                <li>Duplicated RETS images prefix fix (Premium)</li>

                <li>other fixes</li>
            </ul>',

            __( '3.6.4 (May 29, 2018)', 'es-plugin' ) => '<ul>
                <li>GDPR compliance implemented (Checkboxes to Request and Register forms added)</li>
                <li>New Agent Permissions added (PRO)</li>
                <li>View values RETS entities popup bug fixed (Premium)</li>
                <li>Rooms config implemented (Premium)</li>
                <li>MLS Update Date field fix (Premium)</li>
                <li>RETS Synchronizator search algorithm changed</li>
                <li>RETS Synchronizator updater fixed</li>
                <li>RETS Synchronizator fixed duplicated entities bug</li>
                <li>other fixes</li>
            </ul>',

            __( '3.6.3 (May 05, 2018)', 'es-plugin' ) => '<ul>
                <li>Thumb images in gallery centering fixed</li>
                <li>html entities into wp_editor on front-end management decoded (PRO & Premium)</li>
                <li>PDF issue fixed</li>
                <li>Area and lot size fields steps fixed</li>
                <li>Edit property link added to admin bar</li>
                <li>Added option to override templates in child theme: <ul>
                        <li>- estatik/property/gallery.php</li>
                        <li>- estatik/property/tabs.php</li>
                        <li>- estatik/property/share.php</li>
                        <li>- estatik/content-single.php</li>
                        <li>- estatik/archive-properties.php</li>
                        <li>- estatik/content-archive.php</li>
                        <li>- estatik/shortcodes/register.php</li>
                    </ul>
                </li>
                <li>Bug with custom fields assignment in FB (PRO & Premium)</li>
                <li>Fixed issue with images import duplication (Premium)</li>
                <li>Entitiy RETS data popup implemented (Premium)</li>
                <li>Title import fixed (Premium)</li>
                <li>Fields order in Config fixed (Premium)</li>
                <li>Bug with custom fields and sections display n frontend management fixed (PRO and Premium)</li>
                <li>Fixed template for es_feature taxonomy</li>
            </ul>',

            __( '3.6.2 (March 23, 2018)', 'es-plugin' ) => '<ul>
                <li>Multiselect for filterable fields in Import/update settings added (Premium)</li>
                <li>RETS profile active now sign added (Premium)</li>
                <li>Filter by RETS user in Listings in admin area added (Premium)</li>
                <li>FB sections to admin area added (Premium)</li>
                <li>Map view optimization (Premium)</li>
                <li>Bulk removal for import/update schedules added (Premium)</li>
                <li>Google Address validation option added (Premium)</li>
                <li>Map view optimization (PRO and Premium)</li>
                <li>other fixes</li>
            </ul>',

            __( '3.6.1 (March 12, 2018)', 'es-plugin' ) => '<ul>
                <li>Front management css styles for attachment fields fixed</li>
                <li>Attachment link removal for the es_prop_management shortcode fixed</li>
                <li>Fixed uploading files process for FB fields on front management page</li>
                <li>Grecaptcha log error fixed. Request form duplicated emails issue fixed</li>
                <li>Added extra step 0.01 to number field type in FB</li>
                <li>Dismiss option for admin messages added</li>
                <li>Big size image for center single property layout added</li>
                <li>other fixes</li>
            </ul>',

            __( '3.6.0 (February 10, 2018)', 'es-plugin' ) => '<ul>
                <li>Responive layout for es_search_form shortcode fixed</li>
                <li>Dimensions & Currencies removal option deleted</li>
                <li>Added option for restore basic fields in FB</li>
                <li>Added color settings fix to support new Estatik Project theme</li>
                <li>ShelterZoom widget support added (PRO & Premium)</li>
                <li>Agent drop-down bug fixed in search form (PRO & Premium)</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.5.0 (January 25, 2018)', 'es-plugin' ) => '<ul>
                <li>New feature - Color settings added</li>
                <li>Extra price format is added (99,999)</li>
                <li>Pagination fixed (PRO)</li>
                <li>White label added (PRO & Premium)</li>
                <li>Share buttons fixed (PRO & Premium)</li>
                <li>Map view on single property page fixed (PRO & Premium)</li>
                <li>Zoom option added (PRO & Premium)</li>
                <li>other fixes</li>
            </ul>',

            __( '3.4.0 (January 10, 2018)', 'es-plugin' ) => '<ul>
                <li>Search by multicategories issue fixed</li>
                <li>New shortcode for search results page added - [es_search]</li>
                <li>Zoom in/out option for map view added (PRO & Premium)</li>
                <li>New map search feature added - [es_search_map] (PRO & Premium)</li>
                <li>Settings tabs fixed in frontend management (PRO & Premium)</li>
                <li>Subscription success page fixed (PRO & Premium)</li>
                <li>Synchronizer bugs fixes (Premium)</li>
                <li>Lookup fields in synchronizer fixes (Premium)</li>
                <li>Social sharing icons bug fixed</li>
                <li>other fixes</li>
            </ul>',

            __( '3.3.7 (December 29, 2017)', 'es-plugin' ) => '<ul>
                <li>Demo listings and pages setup added (Simple)</li>
                <li>Request form widget added to Simple (Simple)</li>
                <li>Hide option for Name and Phone fields in Request widget added</li>
                <li>Email text edit option in Request form added</li>
                <li>other fixes</li>
            </ul>',

	        __( '3.3.6 (December 13, 2017)', 'es-plugin' ) => '<ul>
                <li>Search by price field added</li>
                <li>Bug with fields removal fixed (PRO & Premium)</li>
                <li>New shortcode for single property page added [es_single id=\'ID\']</li>
                <li>Language files updated</li>
                <li>Changed labels for dimensions</li>
                <li>Added fix for AccessPress Lite theme support</li>
                <li>Property q-ty field autogeneration (PRO & Premium)</li>
                <li>Empty sections fixed</li>
                <li>Slick slider nav arrows fixed</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.3.5 (November 15, 2017)', 'es-plugin' ) => '<ul>
                <li>AJAX address search with auto-suggest feature implemented</li>
                <li>Conflict with SiteOringin builder fixed</li>
                <li>PayPal pwd field type changed</li>
                <li>New Price note field added</li>
                <li>Option to remove default fields added</li>
                <li>reCaptcha added Register form (PRO & Premium version)</li>
                <li>Add new field in frontend management bug fixed (PRO & Premium version)</li>
                <li>Profiles added for multiple RETS accounts (Premium version)</li>
                <li>minor fixes</li>
            </ul>',

			__( '3.3.4 (November 1, 2017)', 'es-plugin' ) => '<ul>
                <li>Publish/Unpublish agents fix</li>
                <li>Characters issue in PDF fixed</li>
                <li>Order issue in Fields Builder fixed</li>
                <li>Address removal fix for listings deleted</li>
                <li>Search by price issue fixed</li>
                <li>reCAPTCHA integrated</li>
                <li>CSV import issues fixed</li>
                <li>Listings images removal for listings deleted</li>
                <li>Reset button in search fixed</li>
                <li>Added popup open zoom effect</li>
                <li>Actualization feature added (Estatik Premium)</li>
                <li>Polish language files added</li>
                <li>Italian language files updated</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.3.3 (October 9, 2017)', 'es-plugin' ) => '<ul>
                <li>Fields builder bug with repeated fields fixed</li>
                <li>Scroll bar to drop-down fields in search added</li>
                <li>Images repeatition bug in lightbox gallery fixed</li>
                <li>Description text output in list view added</li>
                <li>Romanian language files added</li>
            </ul>',

            __( '3.3.2 (August 11, 2017)', 'es-plugin' ) => '<ul>
                <li>Alignment issue with fields in frontend fixed</li>
                <li>some minor fixes</li>
            </ul>',

            __( '3.3.1 (July 21, 2017)', 'es-plugin' ) => '<ul>
                <li>Price issue is fixed</li>
            </ul>',

            __( '3.3.0 (Juy 17, 2017)', 'es-plugin' ) => '<ul>
                <li>Fields builder added</li>
                <li>Currency manager added</li>
                <li>Search shortcode added [es_search]</li>
                <li>hu_HU language translation files added</li>
                <li>PDF brochure with Logo upload option updated (PRO)</li>
                <li>other minor fixes</li>
            </ul>',

            __( '3.2.1 (July 8, 2017)', 'es-plugin' ) => '<ul>
                <li>Search shortcode added</li>
                <li>New currenies are added (COP, Thai Baht, Turkish Lira, Hungarin Forint)</li>
                <li>PDF brochure updated (PRO)</li>
                <li>Labels icons fixed (PRO)</li>
                <li>hu_HU language files added</li>
                <li>minor fixes</li>
            </ul>',
          __( '3.2.0 (June, 17, 2017)', 'es-plugin' ) => '<ul>
                <li>WPML support added</li>
                <li>New currencies (Rp, AED, ZAR) added</li>
                <li>Drag & drop feature for new fields added (PRO & Premium)</li>
                <li>Powered by link fixed</li>
                <li>Remove icons in listings if empty values</li>
                <li>minor fixes</li>
            </ul>',

            __( '3.1.0 (MAY, 24, 2017)', 'es-plugin' ) => '<ul>
                <li>Frontend management added back</li>
                <li>Pagination bug fixed</li>
                <li>Some texts edited</li>
                <li>Display of excerpt text in subscriptions fixed</li>
                <li>Show/hide Title removed</li>
                <li>Extra layouts settings added</li>
                <li>Sharing via Facebook, Twitter, LinkedIn added</li>
                <li>Currency symbols issue fixed</li>
            </ul>',

            __( '3.0.2 (May, 10, 2017)', 'es-plugin' ) => '<ul>
                <li>Area field fixed</li>
                <li>Search widget enhanced by search by tags</li>
                <li>Bug with copying fields fixed (PRO)</li>
                <li>Hide title option removed from Settings</li>
                <li>Spanish and Russian language files updated</li>
                <li>Shortcode [es_my_listing category="for rent" posts_per_page="3" show_filter=0] fixed</li>
                <li>Bug with agents registration fixed (PRO)</li>
            </ul>',

            __( '3.0.1 (APRIL, 18, 2017)', 'es-plugin' ) => '<ul>
                <li>New currencies added: ₹ (INR), ¥ (JPY), Fr. (CHF), ₱ (PHP)</li>
                <li>Fixed some styles in description html text box</li>
                <li>Migration from ver. 2.4.0 optimized</li>
                <li>Optimized image styles</li>
            </ul>',

            __( '3.0.0 (April, 12, 2017)', 'es-plugin' ) => '<ul>
                <li>Property became WP_Post entity</li>
                <li>Images upload via WP Media only</li>
                <li>Numerous new shortcodes added</li>
                <li>Search with drag & drop feature improved</li>
                <li>Archive page created, can be customized using wp hooks</li>
                <li>Pagination improved</li>
                <li>Google Map improved, option to add address with lat/lng fields added (PRO only)</li>
                <li>Labels became editable (PRO only)</li>
                <li>CSV Import improved, images import via link added (PRO only)</li>
                <li>Subscriptions: recurring payments added (PRO only)</li>
                <li>Frontend management replaced by limited admin area (PRO only)</li>
                <li>Admin logo upload added (PRO only)</li>
                <li>Other fixes..</li>
            </ul>',

            __( '2.4.0 (September 26, 2016)', 'es-plugin' ) => '<ul>
                <li>Issue with Upgrade to Pro option fixed</li>
            </ul>',

            __( '2.3.1 (August 21, 2016)', 'es-plugin' ) => '<ul>
                <li>Arbitrary file upload vulnerability fixed</li>
            </ul>',

            __( '2.3.0 (August 15, 2016)', 'es-plugin' ) => '<ul>
                <li>File upload vulnerability fixed</li>
                <li>Review and removal of session_start() and ob_start()</li>
                <li>MAP API issue fixed</li>
            </ul>',

            __( '2.2.3 (March 30, 2016)', 'es-plugin' ) => '<ul>
                <li>Permalinks issue fixed</li>
                <li>Price issue > 1 mln fixed</li>
                <li>beds and baths translation fixed</li>
                <li>Search bug fixed</li>
                <li>Subscription plans added (PRO)</li>
                <li>PDF bug with currency change fixed (PRO)</li>
                <li>New shortcode to display listings of a specific agent added (PRO)</li>
                <li>Automatic/manual approval of listings added (PRO)</li>
            </ul>Please read detailed description of release <a href="http://estatik.net/estatik-simple-pro-ver-2-2-0-released/" target="_blank">here</a>.',

            __( '2.2.2 (November 21, 2015)', 'es-plugin' ) => '<ul>
                <li>View first menu ON/OFF option added</li>
                <li>Bug with currency format 99 999 fixed</li>
                <li>Popup icon in admin map returned</li>
                <li>Search results on 2,3, etc. pages fixed</li>
                <li>Some grammatical errors corrected</li>
                <li>Half baths added to front-end management page (PRO)</li>
                <li>Correct redirection for agents after logged into front-end management page fixed (PRO)</li>
            </ul>',

            __( '2.2.1 (October 22, 2015)', 'es-plugin' ) => '<ul>
                <li>Search by category fixed</li>
            </ul>',

            __( '2.2.0 (October 22, 2015)', 'es-plugin' ) => '<ul>
                <li>Map issues fixed in frontend, admin and lightbox</li>
                <li>Half bathroom option added</li>
                <li>Dark/light style mode added</li>
                <li>Search widget updated with separate Country, State and City drop-down fields</li>
                <li>New shortcode for city added [es_city city="city name"]</li>
                <li>Dimension display of Area and Lot size fields bug fixed</li>
                <li>Slashes // in new fields removed</li>
                <li>Agent phone field bug fixed</li>
                <li>Deprecated method for wp_widget updated</li>
            </ul>Please read full description of new release <a href="http://estatik.net/estatik-simple-pro-ver-2-2-0-released/" target="_blank">here</a>.',

            __( '2.1.0 (July 7, 2015)', 'es-plugin' ) => '<ul>
                <li>New shortcodes for categories added: [es_category category="for sale"],[es_category type="house"],[es_category status="open"].</li>
                <li>New shortcode for search results page added.</li>
                <li>French translation added.</li>
                <li>Google Map API option added.</li>
                <li>Search widget results page bug fixed.</li>
                <li>Description box bug with text fixed.</li>
                <li>Display of area/lot size dimensions on front-end fixed.</li>
                <li>PRO: PDF translation issue fixed.</li>
                <li>PRO: PDF display in IE and Chrome error fixed.</li>
                <li>PRO: Google Map API option added.</li>
                <li>PRO: Copying images after CSV import fixed.</li>
                </ul>Please read full description of new release <a href="http://estatik.net/estatik-2-1-release-no-more-coding-from-now/" target="_blank">here</a>.',

            __( '2.0.1', 'es-plugin' ) => '<ul>
                <li>Italian translation added</li>
                <li>Spanish translation added</li>
                <li>Arabic translation added</li>
                </ul>Please read full description of new release <a href="http://estatik.net/estatik-2-0-terrific-released-map-view-lots-of-major-fixes-done/" target="_blank">here</a>.',

            __( '2.0 (May 16, 2015)', 'es-plugin' ) => '<ul>
                <li>Safari responsive layout issue fixed.</li>
                <li>Google Map icons issue fixed.</li>
                <li>PRO - HTML editor added.</li>
                <li>PRO - Lightbox on single property page added.</li>
                <li>PRO - Tabs issue fixed.</li>
                <li>PRO - Map view shortcodes added.</li>
                <li>PRO - Map view widget added.</li>
                <li>PRO - Option to use different layouts added.</li>
                </ul>',

            __( '1.1.1', 'es-plugin' ) => '<ul>
                <li>Issue with Google Map API fixed</li>
                <li>Translation into Russian added</li>
                </ul>',

            __( '1.0.1', 'es-plugin' ) => '<ul>
                <li>jQuery conflicts fixed.</li>
                <li>Language files added.</li>
                </ul>',

            __( '1.0.0 (March 24, 2015)', 'es-plugin' ) => '<ul>
                <li>Data manager is added.</li>
                <li>Property listings shortcodes are added.</li>
                <li>Search widget is added.</li></ul>
            ',
        ) );
    }

    /**
     * Return themes list for dashboard page.
     *
     * @return mixed
     */
    public static function get_themes_list() {

	    return apply_filters( 'es_get_themes_list', array(
		    'realtor' => array(
			    'link' => 'https://estatik.net/product/estatik-realtor-theme/',
			    'image' => ES_ADMIN_IMAGES_URL . 'realtor.jpg'
		    ),
		    'mls' => array(
			    'link' => 'https://estatik.net/product/portal-theme/',
			    'image' => ES_ADMIN_IMAGES_URL . 'portal_theme.jpg'
		    ),
            'project' => array(
                'link' => 'https://estatik.net/product/estatik-project-theme/',
                'image' => ES_ADMIN_IMAGES_URL . 'project-theme.jpg'
            ),
		    'trendy' => array(
			    'link' => 'https://estatik.net/product/theme-trendy-estatik-pro/',
			    'image' => ES_ADMIN_IMAGES_URL . 'es_theme_trendy.png'
		    ),
		    'native' => array(
			    'link' => 'https://estatik.net/?post_type=product&p=12&preview=true',
			    'image' => ES_ADMIN_IMAGES_URL . 'es_theme_native.png'
		    ),
	    ) );
    }
}
