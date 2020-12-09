<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Cp_css_js_ext
{

    public $settings = [];

    public $version = MX_CP_CSSJS_VERSION;

    public $defaults = [
        'css'     => '',
        'js'      => '',
        'css_url' => '',
        'js_url'  => '',
        'enable'  => true
    ];


    /**
     * Cp_css_js_ext constructor.
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        $this->config = ee()->config->item('css_js_settings');

        if (!is_array($settings)) {
            $settings = $this->defaults;
        }
        if (!is_array($this->config)) {
            $this->config = [];
        }

        $this->settings = array_replace($settings, $this->config);
    }

    /**
     * [activate_extension description]
     * @return [type] [description]
     */
    public function activate_extension()
    {
        $this->settings = $this->initializeSettings();

        $data = [
            [
                'class'    => __CLASS__,
                'method'   => 'cp_css_end',
                'hook'     => 'cp_css_end',
                'settings' => serialize($this->settings),
                'priority' => 10,
                'version'  => $this->version,
                'enabled'  => 'y'
            ],
            [
                'class'    => __CLASS__,
                'method'   => 'cp_js_end',
                'hook'     => 'cp_js_end',
                'settings' => serialize($this->settings),
                'priority' => 10,
                'version'  => $this->version,
                'enabled'  => 'y'
            ]
        ];

        foreach ($data as $hook) {
            ee()->db->insert('extensions', $hook);
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function cp_css_end($data = '')
    {
        return $this->_add($data, 'css');
    }

    /**
     * @param $data
     * @return string
     */
    public function cp_js_end($data = '')
    {
        return $this->_add($data, 'js');
    }


    private function _add($data, $type)
    {
        if (ee()->extensions->last_call !== false) {
            $data = $this->EE->extensions->last_call;
        }

        if ($this->settings['enable']) {
            $data .= NL . $this->settings[$type];

            if ($type == 'css' && $this->settings['css_url'] != '') {
                $data .= NL . '@import url("' . $this->settings['css_url'] . '")';
            }

            if ($type == 'js' && $this->settings['js_url'] != '') {
                $data .= NL . 'function loadJs( url ){
  return new Promise(( resolve, reject ) => {
    if (document.querySelector( `head > script[ src = "${url}" ]`) !== null ){
        console.warn( `script already loaded: ${url}` );
        resolve();
    }
    const script = document.createElement( "script" );
    script.src = url;
    script.onload = resolve;
    script.onerror = function( reason ){
        // This can be useful for your error-handling code
        reason.message = `error trying to load script ${url}`;
        reject( reason );
    };
    document.head.appendChild( script );
  });
};
loadJs("' . $this->settings['js_url'] . '").then( res => {} ).catch( err => {} );';
            }
        }
        return $data;
    }

    /**
     * [disable_extension description]
     * @return [type] [description]
     */
    public function disable_extension()
    {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }

    /**
     * [update_extension description]
     * @param string $current [description]
     * @return [type]          [description]
     */
    public function update_extension($current = '')
    {
        // UPDATE HOOKS
        return true;
    }


    // --------------------------------
    //  Settings
    // --------------------------------

    public function settings()
    {
        $settings = array();

        return $settings;
    }

    /**
     * Settings Form
     *
     * @param Array   Settings
     * @return  void
     */
    function settings_form($current)
    {
        $name = 'cp_css_js';

        if ($current == '') {
            $current = array();
        }

        $values = array_replace($this->defaults, $this->settings);

        $vars = array(
            'base_url'              => ee('CP/URL')->make('addons/settings/' . $name . '/save'),
            'cp_page_title'         => lang('addon_title'),
            'save_btn_text'         => 'btn_save_settings',
            'save_btn_text_working' => 'btn_saving',
            'alerts_name'           => '',
            'sections'              => array(array())
        );

        $vars['sections'] = array(
            array(
                array(
                    'title'  => lang('addon_enable'),
                    'fields' => array(
                        'enable' => array(
                            'type'     => 'toggle',
                            'value'    => $values['enable'],
                            'required' => false
                        )
                    )
                ),

                array(
                    'title'  => lang('addon_css'),
                    'fields' => array(
                        'css' => array(
                            'type'     => 'textarea',
                            'value'    => $values['css'],
                            'required' => false
                        )
                    )
                ),
                array(
                    'title'  => lang('addon_js'),
                    'fields' => array(
                        'js' => array(
                            'type'     => 'textarea',
                            'value'    => $values['js'],
                            'required' => false
                        )
                    )
                ),
                array(
                    'title'  => lang('addon_css_file'),
                    'fields' => array(
                        'css_url' => array(
                            'type'     => 'text',
                            'value'    => $values['css_url'],
                            'required' => false
                        )
                    )
                ),
                array(
                    'title'  => lang('addon_js_file'),
                    'fields' => array(
                        'js_url' => array(
                            'type'     => 'text',
                            'value'    => $values['js_url'],
                            'required' => false
                        )
                    )
                )
            )
        );

        if (version_compare(APP_VER, '6.0.0', '<')) {
        }

        return ee('View')->make('cp_css_js:index')->render($vars);
    }

    /**
     * Save Settings
     *
     * This function provides a little extra processing and validation
     * than the generic settings form.
     *
     * @return void
     */
    function save_settings()
    {
        if (empty($_POST)) {
            show_error(lang('unauthorized_access'));
        }

        ee()->lang->loadfile('cp_css_js');

        ee('CP/Alert')->makeInline('cp_css_js_save')
            ->asSuccess()
            ->withTitle(lang('message_success'))
            ->addToBody(lang('preferences_updated'))
            ->defer();

        ee()->db->where('class', __CLASS__);
        ee()->db->update('extensions', array('settings' => serialize($_POST)));

        ee()->functions->redirect(ee('CP/URL')->make('addons/settings/cp_css_js'));
    }


    /**
     * [initializeSettings description]
     * @return [type] [description]
     */
    private function initializeSettings()
    {
        // Set up app settings
        $settingData = [
            'css'     => '',
            'js'      => '',
            'css_url' => '',
            'js_url'  => '',
            'enable'  => true
        ];

        return serialize($settingData);
    }

}
