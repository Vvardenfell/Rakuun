<?php

/**
 * @package Rakuun Browsergame
 * @copyright Copyright (C) 2012 Sebastian Mayer, Andreas Sicking, Andre Jährling
 * @license GNU/GPL, see license.txt
 * This file is part of Rakuun.
 *
 * Rakuun is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * Rakuun is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rakuun. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Globally needed functions
 */
abstract class Rakuun_Game {
	public static function isLoginDisabled() {
		return (!Rakuun_User_Manager::isMasterUser() && (RAKUUN_ROUND_STARTTIME > time() && time() > RAKUUN_ROUND_ENDTIME) && (!Rakuun_User_Manager::getCurrentUser() || !Rakuun_TeamSecurity::get()->hasPrivilege(Rakuun_User_Manager::getCurrentUser(), Rakuun_TeamSecurity::PRIVILEGE_BACKENDACCESS)));
	}
	
	public static function isRegistrationDisabled() {
		return (!Rakuun_User_Manager::isMasterUser() && (!RAKUUN_REGISTRATION_ENABLED || RAKUUN_ROUND_STARTTIME > time()) && (!Rakuun_User_Manager::getCurrentUser() || !Rakuun_TeamSecurity::get()->hasPrivilege(Rakuun_User_Manager::getCurrentUser(), Rakuun_TeamSecurity::PRIVILEGE_BACKENDACCESS)));
	}
	
	public static function sendErrorMail($backtrace, $customMessage, $errorType, $subject, $additionalInformation = '') {
		$developers = array();
		try {
			$developers = Rakuun_TeamSecurity::get()->getGroupUsers(Rakuun_TeamSecurity::GROUP_DEVELOPER);
		}
		catch (Core_Exception $ce) {
			// couldn't connect to database?
			echo 'Error while trying to send error mail - connection to database probably failed.
			<br/>
			Trying to send error mail to standard error mail recipient.';
		}
		$mail = new Net_Mail();
		$mail->setSubject('[Rakuun] '.$subject);
		$nr = 0;
		$message = '';
		$traceCount = count($backtrace);
		if ($additionalInformation)
			$message = $additionalInformation."\n\n";
		if ($currentUser = Rakuun_User_Manager::getCurrentUser())
			$message = 'User: '.$currentUser->nameUncolored."\n\n";
		$currentModule = Router::get()->getCurrentModule();
		if (!($params = $currentModule->getParams()))
			$params = array();
		$message .= 'URL: '.$currentModule->getUrl($params)."\n\n";
		$message .= $errorType.'! '.$customMessage."\n\n";
		foreach ($backtrace as $backtraceMessage) {
			$message .= '#'.($traceCount - $nr).':'."\t";
			$message .= (isset($backtraceMessage['class']) ? $backtraceMessage['class'].$backtraceMessage['type'].$backtraceMessage['function'] : $backtraceMessage['function']).'('.(isset($backtraceMessage['args']) ? implode(', ', $backtraceMessage['args']) : '').')';
			if (isset($backtraceMessage['file'])) {
				$message .= ' in '.$backtraceMessage['file'].'('.$backtraceMessage['line'].')';
			}
			$message .= "\n";
			$nr++;
		}
		$message .= "\n\n";
		$message .= 'POST Content: '.print_r($_POST, true);
		$message .= "\n\n";
		$message .= 'SERVER Content: '.print_r($_SERVER, true);
		$mail->setMessage($message);
		$recipients = explode(',', RAKUUN_ERRORMAIL_RECIPIENTS);
		$mail->addRecipients($recipients);
		foreach ($developers as $developer) {
			if (!in_array($developer->mail, $recipients))
				$mail->addRecipient($developer->mail, $developer->nameUncolored);
		}
		$mail->send();
	}
}

?>