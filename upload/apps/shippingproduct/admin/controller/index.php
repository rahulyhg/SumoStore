<?php
namespace Shippingproduct;
use Sumo;
use App;
class ControllerShippingproduct extends App\Controller
{
    public function index()
    {
        $this->document->setTitle(Sumo\Language::getVar('APP_SHIPPINGPRODUCT_TITLE'));
        $this->document->addBreadcrumbs(array(
            'href' => $this->url->link('common/apps', '', 'SSL'),
            'text' => Sumo\Language::getVar('SUMO_ADMIN_APPS_DASHBOARD')
        ));
        $this->document->addBreadcrumbs(array(
            'text' => Sumo\Language::getVar('APP_SHIPPINGPRODUCT_TITLE')
        ));
        $this->document->addScript('view/js/jquery/jquery.parsley.js');

        $this->data['stores'] = $this->model_settings_stores->getStores();
        $this->data['current_store'] = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : 0;

        Sumo\Logger::info('Loading model settings');
        $this->load->appModel('Settings');
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->shippingproduct_model_settings->setSettings($this->data['current_store'], $this->request->post['settings']);
            $this->redirect($this->url->link('app/shippingproduct', '', 'SSL'));
        }
        $this->data['settings'] = $this->shippingproduct_model_settings->getSettings($this->data['current_store']);

        $this->load->model('localisation/geo_zone');
        $this->data['zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->data['geo_zone_id'] = isset($this->request->get['geo_zone_id']) ? $this->request->get['geo_zone_id'] : $this->config->get('zone_id');

        $this->load->model('settings/stores');
        $this->data['tax'] = $this->model_settings_stores->getSettings($this->data['current_store'], 'tax_percentage');
        if (!is_array($this->data['tax']) || !count($this->data['tax']) || !isset($this->data['tax']['default'])) {
            $this->data['tax'] = $this->model_settings_general->getSetting('tax_percentage');
        }

        $this->template = 'index.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
}
