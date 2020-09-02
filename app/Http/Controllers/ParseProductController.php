<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPHtmlParser\Dom;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Class ParseProductController
 *
 * @package App\Http\Controllers
 */
class ParseProductController extends Controller
{
    /**
     * @var Dom
     */
    protected $domParser;

    public function __construct(Dom $domParser)
    {
        $this->domParser = $domParser;
    }

    /**
     * Parse necessary URL and gather necessary info about product
     *
     * @param Request $request
     */
    public function parse(Request $request)
    {
        try {
            $this->domParser->loadFromUrl($request->input('price-url'));
            $linkHtml = $this->domParser->outerHtml;
//            $this->domParser->getElementById();
        } catch (\Exception $e) {
        } catch (ClientExceptionInterface $e) {
        }

    }
}
