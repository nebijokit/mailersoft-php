<?php

	require_once dirname(__FILE__).'/base/ML_Rest_Base.php';

	class ML_Messages extends ML_Rest_Base
	{
		private $fromName;

		private $fromEmail;

		private $variables = array();

		private $id;

		private $recipientEmail;

		private $recipientName;

		private $type;

		private $language;

		private $batchRecipients;

		private $attachments;

		private $replyToEmail;

		private $replyToName;

		private $isTest = 0;

		private $tag;

        function __construct( $api_key ) {

			parent::__construct();

			$this->apiKey = $api_key;

			$this->path = $this->url . 'messages/';
		}

		public function setTest() {

			$this->isTest = 1;

			return $this;
		}

        public function setRecipient( $email, $name = '' ) {

           $this->recipientEmail = $email;
           $this->recipientName = $name;

           return $this;
        }

        public function setFromName( $name ) {

           $this->fromName = $name;

           return $this;
        }

        public function setFromEmail( $email ) {

           $this->fromEmail = $email;

           return $this;

        }

        public function setVariables( $variables ) {

           if ( is_array( $variables ) ) {
               foreach ( $variables as $name => $value ) {
                   $this->setVariable( $name, $value );
               }
           }

           return $this;
        }

        public function setVariable( $name, $value ) {

           $this->variables[ $name ] = $value;

           return $this;
        }

		public function setTag( $tag ) {

			$this->tag = $tag;

			return $this;
		}

        public function setId( $id ) {

           $this->id = $id;

           return $this;
        }

		public function setType( $type ) {

			$this->type = $type;

			return $this;
		}

        public function setLanguage( $code ) {

			$this->language = $code;

			return $this;
        }

        public function addRecipient( $recipient ) {

			if ( isset( $recipient['email'] ) ) {

				$recipient['recipientEmail'] = $recipient['email'];

				unset( $recipient['email'] );
			}

			if ( isset( $recipient['name'] ) ) {

				$recipient['recipientName'] = $recipient['name'];

				unset( $recipient['name'] );
			}

            $this->batchRecipients[] = $recipient;

            return $this;
        }

        public function addRecipients( $recipients ) {

            foreach ( $recipients as $recipient ) {
                $this->addRecipient( $recipient );
            }

            return $this;
        }

        public function setReplyTo( $email, $name = '' ) {

			$this->replyToEmail = $email;
			$this->replyToName = $name;

			return $this;
        }

        public function addAttachment( $filename, $content ) {

			$this->attachments[] = array('filename' => $filename, 'content' => $content );

			return $this;
        }

        public function send() {

			$data = array(
				'apiKey' => $this->apiKey,
				'id' => $this->id,
                'language' => $this->language,
                'fromName' => $this->fromName,
                'fromEmail' => $this->fromEmail
			);

			if ( !empty( $this->batchRecipients ) ) {
				$data['batch'] = $this->batchRecipients;
				$data['tag'] = $this->tag;
			} else {
				$data['recipientName'] = $this->recipientName;
				$data['recipientEmail'] = $this->recipientEmail;
				$data['variables'] = $this->variables;
				$data['attachments'] = $this->attachments;
                $data['fromName'] = $this->fromName;
                $data['fromEmail'] = $this->fromEmail;
				$data['tag'] = $this->tag;
			}

			return $this->execute( 'POST', $data );
        }
	}