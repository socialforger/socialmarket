use Social_Market\Assets;

...

private function init_hooks() {
    add_action( 'init', array( $this, 'load_textdomain' ) );

    add_action( 'init', array( $this, 'register_cpts' ), 5 );

    Assets::init(); // <— registra CSS/JS
}
