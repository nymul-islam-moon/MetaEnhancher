<?php

namespace MetaEnhancer;

class Installer {

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
    }

    /**
     * Add time and version on DB
     */
    public function add_version() {
        $installed = get_option( 'meta_enhancer_installer' );

        if ( ! $installed ) {
            update_option( 'meta_enhancer_installer', time() );
        }

        update_option( 'meta_enhancer_installer', META_ENHANCER_VERSION );
    }
}