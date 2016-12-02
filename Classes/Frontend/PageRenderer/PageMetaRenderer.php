<?php
namespace YoastSeoForTypo3\YoastSeo\Frontend\PageRenderer;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS;
use YoastSeoForTypo3\YoastSeo;

/**
 * Class PageMetaRenderer
 *
 * Extend the meta section of the rendered page in FE context
 *
 * @package YoastSeoForTypo3\YoastSeo\Frontend\PageRenderer
 */
class PageMetaRenderer implements CMS\Core\SingletonInterface
{

    /**
     * Bootstrap a Fluid standalone view using FLUIDTEMPLATE
     * and append the returned content to the <meta /> section
     *
     * @param array $parameters
     *
     * @return string
     */
    public function render(array $parameters)
    {
        /**
         * Check if `config.yoast_seo` is true before any rendering takes place
         * next make sure `plugin.tx_yoastseo` is properly configured
         * `plugin.tx_yoastseo.view` is used as configuration array for FLUIDTEMPLATE
         *
         * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Fluidtemplate/Index.html
         *
         * The content object renderer of TSFE is used to render FLUIDTEMPLATE
         * after `plugin.tx_yoastseo.settings` is merged with `plugin.tx_yoastseo.view.settings`
         */
        if ($GLOBALS['TSFE'] instanceof CMS\Frontend\Controller\TypoScriptFrontendController
            && is_array($GLOBALS['TSFE']->config)
            && array_key_exists('config', $GLOBALS['TSFE']->config)
            && is_array($GLOBALS['TSFE']->config['config'])
            && array_key_exists('yoast_seo.', $GLOBALS['TSFE']->config['config'])
            && is_array($GLOBALS['TSFE']->config['config']['yoast_seo.'])
            && array_key_exists('enabled', $GLOBALS['TSFE']->config['config']['yoast_seo.'])
            && !empty($GLOBALS['TSFE']->config['config']['yoast_seo.']['enabled'])
            && $GLOBALS['TSFE']->tmpl instanceof CMS\Core\TypoScript\TemplateService
            && is_array($GLOBALS['TSFE']->tmpl->setup)
            && array_key_exists('plugin.', $GLOBALS['TSFE']->tmpl->setup)
            && is_array($GLOBALS['TSFE']->tmpl->setup['plugin.'])
            && array_key_exists('tx_yoastseo.', $GLOBALS['TSFE']->tmpl->setup['plugin.'])
            && is_array($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.'])
            && array_key_exists('view.', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.'])
            && is_array($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.']['view.'])
            && array_key_exists('settings.', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.'])
            && is_array($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.']['settings.'])
            && $GLOBALS['TSFE']->cObj instanceof CMS\Frontend\ContentObject\ContentObjectRenderer
        ) {
            $parameters['metaTags'][] = $GLOBALS['TSFE']->cObj->cObjGetSingle(
                'FLUIDTEMPLATE',
                array_merge_recursive(
                    $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.']['view.'],
                    array(
                        'settings.' => $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_yoastseo.']['settings.']
                    )
                )
            );
        }
    }
}