<?php
namespace Shippingpickup;
use Sumo;
use App;
class ControllerShippingpickup extends App\Controller
{
    public function index()
    {
        $this->document->setTitle(Sumo\Language::getVar('APP_SHIPPINGPICKUP_TITLE'));
        $this->document->addBreadcrumbs(array(
            'href' => $this->url->link('common/apps', '', 'SSL'),
            'text' => Sumo\Language::getVar('SUMO_ADMIN_APPS_DASHBOARD')
        ));
        $this->document->addBreadcrumbs(array(
            'text' => Sumo\Language::getVar('APP_SHIPPINGPICKUP_TITLE')
        ));
        $this->document->addScript('view/js/jquery/jquery.parsley.js');

        $this->data['stores'] = $this->model_settings_stores->getStores();
        $this->data['current_store'] = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : 0;

        $this->load->appModel('Settings');
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->shippingpickup_model_settings->setSettings($this->data['current_store'], $this->request->post['settings']);
            //$this->redirect($this->url->link('app/shippingpickup', '', 'SSL'));
        }
        $this->data['settings'] = $this->shippingpickup_model_settings->getSettings($this->data['current_store']);

        $this->load->model('localisation/geo_zone');
        $this->data['zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->data['geo_zone_id'] = isset($this->request->get['geo_zone_id']) ? $this->request->get['geo_zone_id'] : $this->config->get('zone_id');

        $this->template = 'index.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
}
