<?php
/*
 * This file is part of the ivar/easy-51tracking
 *
 * (c) ivar <625079860@qq.com>
 *
 * Date: 2020/6/1 下午8:46
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ivar\Easy51Tracking;

use Ivar\Easy51Tracking\Support\Config;
use Ivar\Easy51Tracking\Traits;
use Ivar\Easy51Tracking\Exceptions\InvalidArgumentException;

class Easy51Tracking{

    use Traits\HasHttpRequest;
    use Traits\HasBuildParam;

    /**
     * tracking config
     * @var
     */
    protected $config;

    /**
     * request header
     * @var array
     */
    protected $header;

    /**
     *  base URL
     */
    const ENDPOINT_URL = 'https://api.51tracking.com/v2';

    /**
     * Easy51Tracking constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->header = [
            'Content-Type'      =>  'application/json',
            'Tracking-Api-Key'  =>  $this->config->get('tracking_api_key'),
        ];
    }

    /**
     * get all carrier and carrier code
     *
     * @see api doc : https://www.51tracking.com/api-carriers-list-all-carriers
     *
     * @return array
     */
    public function getAllCarriers(){

        $endpoint = $this->_buildEndpoint('/carriers');

        $this->header['Lang'] = $this->config->get('lang','cn');

        return $this->get($endpoint,[],$this->header);
    }


    /**
     * create tracking order
     *
     * @see api doc：https://www.51tracking.com/api-track-create-a-tracking-item#post
     *
     * @param array $params
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function create($params = []){

        $arrReq = $this->_buildOrderParams($params);

        $endpoint = $this->_buildEndpoint('/trackings/post');

        return $this->postJson($endpoint,$arrReq,$this->header);
    }

    /**
     * get one order tracking by tracking number
     *
     * @see api doc：https://www.51tracking.com/api-track-get-a-single-tracking-results#single-get
     *
     * @param  string     $carrierCode
     * @param  string     $trackingNumber
     * @param  string     $lang             lang
     *
     * @return array
     */
    public function getOrderTrackingByNumber($carrierCode,$trackingNumber,$lang = 'cn'){

        $endpoint = $this->_buildEndpoint('/trackings/'.$carrierCode.'/'.$trackingNumber.'/'.$lang);

        return $this->get($endpoint,[],$this->header);
    }

    /**
     * update one order tracking by tracking number
     *
     * @see api doc : https://www.51tracking.com/api-track-update-tracking-item#single-put
     *
     * @param string $carrierCode
     * @param string $trackingNumber
     * @param array $params
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function updateOneOrderTrackingByNumber($carrierCode,$trackingNumber,$params){

        $arrReq = $this->_buildUpdateOrderParams($params);

        $endpoint = $this->_buildEndpoint('/trackings/'.$carrierCode.'/'.$trackingNumber);

        return $this->putJson($endpoint,$arrReq,$this->header);
    }

    /**
     * delete one order tracking by tracking number
     *
     * @see api doc : https://www.51tracking.com/api-track-delete-a-tracking-item#single-delete
     *
     * @param string $carrierCode
     * @param string $trackingNumber
     *
     * @return array
     */
    public function deleteOneOrderTrackingByNumber($carrierCode,$trackingNumber){

        $endpoint = $this->_buildEndpoint('/trackings/'.$carrierCode.'/'.$trackingNumber);

        return $this->delete($endpoint,[],$this->header);
    }

}