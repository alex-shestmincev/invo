<?php
use Phalcon\Mvc\User\Component,
	Phalcon\Mvc\View;
require_once __DIR__ . '/../../vendor/Swift/swift_required.php';
/**
 *
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{
	protected $_transport;
	
	
	/**
	 * Applies a template to be used in the e-mail
	 *
	 * @param string $name
	 * @param array $params
	 */
	public function getTemplate($name, $params)
	{
		$parameters = array_merge(array(
			'publicUrl' => $this->config->application->baseUri,
		), $params);
        
        $view = new \Phalcon\Mvc\View();
        return $view->getRender('emailTemplates', $name,
            $parameters,
            function($setview) {
            $setview->setDI( $this->di );
            $setview->registerEngines(array(
                ".volt"  => 'simple_volt'
            ));
                $setview->setViewsDir(APP_PATH . $this->config->application->viewsDir);
                $setview->setRenderLevel(Phalcon\Mvc\View::LEVEL_LAYOUT);
            }
        );
	}
	/**
	 * Sends e-mails via gmail based on predefined templates
	 *
	 * @param array $to
	 * @param string $subject
	 * @param string $name
	 * @param array $params
	 */
	public function send($to, $subject, $name, $params)
	{
		//Settings
		$mailSettings = $this->config->mail;
		$template = $this->getTemplate($name, $params);
		// Create the message
		$message = Swift_Message::newInstance()
  			->setSubject($subject)
  			->setTo($to)
  			->setFrom(array(
  				$mailSettings->fromEmail => $mailSettings->fromName
  			))
  			->setBody($template, 'text/html');
  			if (!$this->_transport) {
				$this->_transport = Swift_SmtpTransport::newInstance(
					$mailSettings->smtp_server,
					$mailSettings->smtp_port,
					$mailSettings->smtp_security
				)
		  			->setUsername($mailSettings->smtp_username)
		  			->setPassword($mailSettings->smtp_password);
		  	}
		  	// Create the Mailer using your created Transport
			$mailer = Swift_Mailer::newInstance($this->_transport);
            
			return $mailer->send($message);
	}
}