<?php

namespace admin\controller;

/**
 * Ajax calls used in admin module.<br />
 * All responses are json
 * @package admin_controllers
 */
class Ajax extends Index {

    protected function postDispatch() {}

    /**
     * Process admin AJAX call
     * @Request("ajax-admin")
     * @Parameter(name="action", validator="\xframe\validation\RegEx('/(\S+|^$)/')")
     * @View("xframe\view\JSONView")
     */
    public function admin() {
        parent::admin();

        if (isset($this->view->data)) {
            $this->view->addParameter("answer", $this->view->data);
        }
    }
}
