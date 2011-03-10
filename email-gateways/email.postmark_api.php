<?php

	require_once(TOOLKIT . '/class.emailgateway.php');
	require_once(TOOLKIT . '/class.emailhelper.php');

	Class Postmark_APIGateway extends EmailGateway{

		protected $_api_key;

		public function __construct(){
			require_once(EXTENSIONS . '/email_postmark/lib/postmark-php/Postmark.php');
			parent::__construct();
			$this->setSenderEmailAddress(Symphony::Configuration()->get('from_address', 'email_postmark'));
			$this->setSenderName(Symphony::Configuration()->get('from_name', 'email_postmark'));
		}

		public function about(){
			return array(
				'name' => 'Postmark',
			);
		}

		public function send(){

			try {
				$this->validate();

				define('POSTMARKAPP_MAIL_FROM_NAME', $this->_sender_name);
				define('POSTMARKAPP_MAIL_FROM_ADDRESS', $this->_sender_email_address);
				define('POSTMARKAPP_API_KEY', $this->_api_key ? $this->_api_key : Symphony::Configuration()->get('api_key', 'email_postmark'));

				$email = new Mail_Postmark();

				/**
				 * Set recipients. Since the library/API is not very robust
				 * concerning recipient names, we are encoding them.
				 * (Which is a shame.)
				 */
				foreach($this->_recipients as $name => $address) {
					if (is_numeric($name)) {
						$email->addTo($address);
					}
					else {
						$email->addTo($address, EmailHelper::qEncode($name));
					}
				}
				if($this->_reply_to_email_address){
					if($this->_reply_to_name){
						$email->replyTo($this->_reply_to_email_address, EmailHelper::qEncode($this->_reply_to_name));
					}
					else{
						$email->replyTo($this->_reply_to_email_address);
					}
				}

				/**
				 * Set additional header fields. Unfortunately certain
				 * fields must not be set using the library's addHeader()
				 * method.
				 *
				 * Be very careful using additional header fields -
				 * Postmark won't get the encoding right...
				 */
				foreach($this->_header_fields as $name => $body) {
					if (strtolower($name) == 'cc') {
						$email->addCc($body);
					}
					else if (strtolower($name) == 'bcc') {
						$email->addBcc($body);
					}
					else {
						$email->addHeader($name, $body);
					}
				}

				$email->subject($this->_subject);
				$email->messagePlain($this->_text_plain);
				$email->messageHtml($this->_text_html);
				foreach($this->_attachments as $file){
					$email->addAttachment($file);
				}

				// Send the email
				$email->send();
			}
			catch (Exception $e) {
				throw new EmailGatewayException($e->getMessage());
			}
			return true;
		}

		public function setApiKey($key){
			$this->_api_key = $key;
		}

		/**
		 * The preferences to add to the preferences pane in the admin area.
		 *
		 * @return XMLElement
		 */
		public function getPreferencesPane(){

			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings pickable');
			$group->setAttribute('id', 'postmark_api');
			$group->appendChild(new XMLElement('legend', __('Postmark Email Gateway')));

			$div = new XMLElement('div');
			$div->appendChild(new XMLElement('p', __('The following default settings will be used to send emails unless they are overwritten.')));
			$group->appendChild($div);

			$label = Widget::Label(__('API Key'));
			$label->appendChild(Widget::Input('settings[email_postmark][api_key]', Symphony::Configuration()->get('api_key', 'email_postmark')));
			$group->appendChild($label);

			$div = new XMLElement('div');
			$div->setAttribute('class', 'group');
			$label = Widget::Label(__('From Name'));
			$label->appendChild(Widget::Input('settings[email_postmark][from_name]', Symphony::Configuration()->get('from_name', 'email_postmark')));
			$div->appendChild($label);

			$label = Widget::Label(__('From Email Address'));
			$label->appendChild(Widget::Input('settings[email_postmark][from_address]', Symphony::Configuration()->get('from_address', 'email_postmark')));
			$div->appendChild($label);
			$group->appendChild($div);

			$group->appendChild(new XMLElement('p', 'Must match a server signature address created in Postmark.', array('class' => 'help')));

			return $group;
		}
	}
