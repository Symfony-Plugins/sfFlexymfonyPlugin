<?php

/**
 * amfgateway actions.
 *
 * @package    RSICA
 * @subpackage amfgateway
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class amfgatewayActions extends sfActions
{
  public function executeIndex()
  {
    $this->setLayout( false );
    $gateway = new sfAmfGateway( );
    $response = sfContext::GetInstance()->getResponse();
    $response->setContentType('application/x-amf');
    $response->setContent( $gateway->service() );
    return sfView::NONE;
  }
}
