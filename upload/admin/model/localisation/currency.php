<?php
namespace Sumo;
class ModelLocalisationCurrency extends Model
{
    public function addCurrency($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . $this->db->escape($data['decimal_place']) . "', value = '" . $this->db->escape($data['value']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

        if ($this->config->get('config_currency_auto')) {
            $this->updateCurrencies(true);
        }

        $this->cache->delete('currency');
    }

    public function editCurrency($currency_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . $this->db->escape($data['decimal_place']) . "', value = '" . $this->db->escape($data['value']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }

    public function deleteCurrency($currency_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }

    public function getCurrency($currency_id)
    {
        return $this->query("SELECT * FROM PREFIX_currency WHERE currency_id = :id", array('id' => $currency_id))->fetch();

        return $query->row;
    }

    public function getCurrencyByCode($currency)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }

    public function getCurrencies($data = array())
    {

        return $this->fetchAll("SELECT * FROM PREFIX_currency");
    }

    public function updateCurrencies($force = false)
    {
        if (extension_loaded('curl')) {
            $data = array();

            if ($force) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "'");
            } else {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < '" .  $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");
            }

            foreach ($query->rows as $result) {
                $data[] = $this->config->get('config_currency') . $result['code'] . '=X';
            }

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $content = curl_exec($curl);

            curl_close($curl);

            $lines = explode("\n", trim($content));

            foreach ($lines as $line) {
                $currency = utf8_substr($line, 4, 3);
                $value = utf8_substr($line, 11, 6);

                if ((float)$value) {
                    $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency) . "'");
                }
            }

            $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");

            $this->cache->delete('currency');
        }
    }

    public function getTotalCurrencies()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");

        return $query->row['total'];
    }
}
