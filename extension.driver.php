<?php

/**
 * Extension driver
 *
 * @package Email Postmark API extension
 * @author Alistair Kearney, Michael Eichelsdoerfer
 */
class extension_Email_Postmark extends Extension
{
    /**
     * Extension information
     */
    public function about()
    {
        return array(
            'name'         => 'Email Gateway: Postmark',
            'version'      => '1.0beta',
            'release-date' => '2011-03-09',
            'author' => array(
                'name' => 'Symphony Community',
                'website' => 'https://github.com/symphonists',
            )
        );
    }

    /**
     * Function to be executed on uninstallation
     */
    public function uninstall()
    {
        /**
         * preferences are defined in the email gateway class,
         * but removing upon uninstallation must be handled here;
         */
        Symphony::Configuration()->remove('email_postmark');
        Symphony::Configuration()->write();
        return true;
    }
}
