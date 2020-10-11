<?php

namespace App\Trlprice\Parsers;

use App\ProductPrice;

/**
 * Class PriceParsers
 *
 * @package App\Trlprice\Parsers
 */
class PriceParsers
{
    const CURRENCIES = [
        '€',
        '$',
        '₽'
    ];

    /**
     * @var ProductPrice
     */
    protected $productPriceModel;

    /**
     * PriceParsers constructor.
     *
     * @param ProductPrice $productPriceModel
     */
    public function __construct(ProductPrice  $productPriceModel)
    {
        $this->productPriceModel = $productPriceModel;
    }

    /**
     * Parse product price from 1a
     *
     * @param $dom
     * @param $productId
     */
    public function parse1aPrice($dom, $productId)
    {
        $productNode = $dom->find('.product-price-details__price');

        if (!empty($productNode)) {
            $productNode = $productNode[0]->text();
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productNode), $result);
            $productPrice = implode('.', $result[0]);
            $currency = $this->checkCurrency($productNode);
            $this->productPriceModel->product_relation_id = $productId;
            $this->productPriceModel->product_price = $productPrice;
            $this->productPriceModel->currency = $currency;
        } else {
            $productNode = $dom->find('.product-price-details__block')[0]->text();
            $productNode = str_replace('.', '', $productNode);
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productNode), $result);
            $productPrice = implode('.', $result[0]);
            $this->productPriceModel->product_relation_id = $productId;
            $this->productPriceModel->product_price = $productPrice;
        }

        $this->productPriceModel->save();

        return $this->productPriceModel;
    }

    /**
     * Parse product price from Dateks
     *
     * @param $dom
     * @param $productId
     */
    public function parseDateksPrice($dom, $productId)
    {
        $productPrice = $dom->find('.price');

        if (!empty($productPrice)) {
            $productPrice = $productPrice[0]->text();
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productPrice), $result);
            $productPrice = implode('.', str_replace(' ', '', $result[0]));
            $currency = $this->checkCurrency($dom->find('.price')[0]->text());
            $this->productPriceModel->product_relation_id = $productId;
            $this->productPriceModel->product_price = $productPrice;
            $this->productPriceModel->currency = $currency;
        }

        $this->productPriceModel->save();

        return $this->productPriceModel;
    }

    /**
     * Parse product price from RD
     *
     * @param $dom
     * @param $productId
     */
    public function parseRdPrice($dom, $productId)
    {
        $productPrice = $dom->find('.price');

        if (!empty($productPrice)) {
            $productPrice = $productPrice[0]->text();
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productPrice), $result);
            $productPrice = implode('.', str_replace(' ', '', $result[0]));
            $currency = $this->checkCurrency($dom->find('.price')[0]->text());
            $this->productPriceModel->product_relation_id = $productId;
            $this->productPriceModel->product_price = $productPrice;
            $this->productPriceModel->currency = $currency;
        }

        $this->productPriceModel->save();

        return $this->productPriceModel;
    }

    /**
     * Check used website currency
     *
     * @param $productNode
     *
     * @return string
     */
    public function checkCurrency($productNode)
    {
        foreach (self::CURRENCIES as $currency) {
            if (strpos($productNode, $currency)) {
                return $currency;
            }
        }
    }
}
